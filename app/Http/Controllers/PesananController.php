<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
use App\Models\ProdukVariasi;

class PesananController extends Controller
{
    // =====================================================================
    // HELPER: Query pencarian fleksibel semua kolom
    // =====================================================================
    private function applySearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('id_pesanan',    'LIKE', "%{$search}%")
              ->orWhere('nama_pembeli','LIKE', "%{$search}%")
              ->orWhere('email',       'LIKE', "%{$search}%")
              ->orWhere('no_hp',       'LIKE', "%{$search}%")
              ->orWhere('nama_barang', 'LIKE', "%{$search}%")
              ->orWhere('alamat',      'LIKE', "%{$search}%")
              ->orWhere('total',       'LIKE', "%{$search}%");
        });
    }

    private function syncNoHp($pesanan)
    {
        if (!empty($pesanan->no_hp)) return $pesanan;

        if ($pesanan->user) {
            $pesanan->no_hp = $pesanan->user->no_hp ?? $pesanan->user->telepon ?? $pesanan->user->no_telp;
        } else {
            $userAsli = User::where('email', $pesanan->email)->first();
            $pesanan->no_hp = $userAsli ? ($userAsli->no_hp ?? $userAsli->telepon ?? $userAsli->no_telp) : null;
        }

        return $pesanan;
    }

    // =====================================================================
    // SISI ADMIN: Lihat Semua Transaksi
    // =====================================================================
    public function lihatSemua(Request $request)
    {
        $search = $request->query('search');

        $semua_pesanan = Pesanan::with('user')
            ->when($search, fn($q) => $this->applySearch($q, $search))
            ->orderBy('created_at', 'desc')
            ->get()
            ->each(fn($p) => $this->syncNoHp($p));

        return view('admin.lihat_semua', compact('semua_pesanan'));
    }

    // =====================================================================
    // SISI ADMIN: Pesanan Masuk (Sedang Diproses & Dalam Perjalanan)
    // =====================================================================
    public function index(Request $request)
    {
        $search = $request->query('search');

        $pesanan_masuk = Pesanan::with('user')
            ->whereIn('status', ['SEDANG DIPROSES', 'DALAM PERJALANAN'])
            ->when($search, fn($q) => $this->applySearch($q, $search))
            ->orderBy('created_at', 'desc')
            ->get()
            ->each(fn($p) => $this->syncNoHp($p));

        $semua_produk = Produk::with('variasis')->get();

        return view('admin.pesanan_masuk', compact('pesanan_masuk', 'semua_produk'));
    }

    // =====================================================================
    // SISI ADMIN: Konfirmasi Pesanan (Belum Konfirmasi)
    // =====================================================================
    public function konfirmasi(Request $request)
    {
        $search = $request->query('search');

        $pesanan_konfirmasi = Pesanan::with('user')
            ->where('status', 'BELUM KONFIRMASI')
            ->when($search, fn($q) => $this->applySearch($q, $search))
            ->orderBy('created_at', 'desc')
            ->get()
            ->each(fn($p) => $this->syncNoHp($p));

        $semua_produk = Produk::with('variasis')->get();

        return view('admin.pesanan_konfirmasi', compact('pesanan_konfirmasi', 'semua_produk'));
    }

    // =====================================================================
    // SISI ADMIN: Pesanan Selesai
    // =====================================================================
    public function selesai(Request $request)
    {
        $search = $request->query('search');

        $pesanan_selesai = Pesanan::with('user')
            ->where('status', 'SELESAI')
            ->when($search, fn($q) => $this->applySearch($q, $search))
            ->orderBy('created_at', 'desc')
            ->get()
            ->each(fn($p) => $this->syncNoHp($p));

        $semua_produk = Produk::with('variasis')->get();

        return view('admin.pesanan_selesai', compact('pesanan_selesai', 'semua_produk'));
    }

    // =====================================================================
    // SISI ADMIN: Pesanan Dibatalkan
    // =====================================================================
    public function dibatalkan(Request $request)
    {
        $search = $request->query('search');

        $pesanan_batal = Pesanan::with('user')
            ->where('status', 'DIBATALKAN')
            ->when($search, fn($q) => $this->applySearch($q, $search))
            ->orderBy('created_at', 'desc')
            ->get()
            ->each(fn($p) => $this->syncNoHp($p));

        $semua_produk = Produk::with('variasis')->get();

        return view('admin.pesanan_batal', compact('pesanan_batal', 'semua_produk'));
    }

    // =====================================================================
    // SISI ADMIN: Pengajuan Batal
    // =====================================================================
    public function pengajuanBatal(Request $request)
    {
        $search = $request->query('search');

        $pesanan_pengajuan = Pesanan::with('user')
            ->where('status', 'PENGAJUAN BATAL')
            ->when($search, fn($q) => $this->applySearch($q, $search))
            ->orderBy('created_at', 'desc')
            ->get()
            ->each(fn($p) => $this->syncNoHp($p));

        $semua_produk = Produk::with('variasis')->get();

        return view('admin.pesanan_pengajuan_batal', compact('pesanan_pengajuan', 'semua_produk'));
    }

    // =====================================================================
    // POIN 2: Admin batalkan pesanan dari halaman Pesanan Masuk
    // Stok dikembalikan + refund_status diset MENUNGGU
    // =====================================================================
    public function batalkanDariMasuk($id)
    {
        $pesanan = Pesanan::findOrFail($id);

        if (!in_array($pesanan->status, ['SEDANG DIPROSES', 'DALAM PERJALANAN'])) {
            return back()->with('error', 'Pesanan ini tidak bisa dibatalkan dari halaman ini.');
        }

        try {
            \DB::transaction(function () use ($pesanan) {
                // Kembalikan stok dari items_snapshot
                $items = $pesanan->items_snapshot ?? [];
                foreach ($items as $item) {
                    if (isset($item['produk_variasi_id'])) {
                        $variasi = ProdukVariasi::find($item['produk_variasi_id']);
                        if ($variasi) {
                            $variasi->stok += $item['quantity'];
                            $variasi->save();
                        }
                    }
                }

                $pesanan->status = 'DIBATALKAN';
                $pesanan->refund_status = 'MENUNGGU'; // otomatis tandai perlu refund
                $pesanan->save();
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membatalkan pesanan: ' . $e->getMessage());
        }

        return back()->with('success', 'Pesanan ' . $pesanan->id_pesanan . ' dibatalkan. Stok dikembalikan. Refund menunggu konfirmasi admin.');
    }

    // =====================================================================
// POIN 4: Admin batalkan pesanan yang SUDAH SELESAI (barang sudah sampai)
// Misal karena barang cacat / salah kirim. Stok TIDAK dikembalikan
// karena barang dianggap sudah diterima pembeli (rusak/hangus).
// =====================================================================
public function batalkanDariSelesai($id)
{
    $pesanan = Pesanan::findOrFail($id);

    if ($pesanan->status !== 'SELESAI') {
        return back()->with('error', 'Pesanan ini tidak dalam status selesai, tidak bisa dibatalkan dari halaman ini.');
    }

    $pesanan->status = 'DIBATALKAN';
    $pesanan->refund_status = 'MENUNGGU'; // sudah pasti sudah bayar, otomatis tandai perlu refund
    $pesanan->save();

    return back()->with('success', 'Pesanan ' . $pesanan->id_pesanan . ' berhasil dibatalkan. Stok tidak dikembalikan (barang dianggap rusak/hangus). Refund menunggu konfirmasi admin di halaman Pesanan Batal.');
}

    // =====================================================================
    // POIN 3: Admin konfirmasi refund sudah selesai dilakukan manual
    // =====================================================================
    public function konfirmasiRefund($id)
    {
        $pesanan = Pesanan::findOrFail($id);

        if ($pesanan->status !== 'DIBATALKAN') {
            return back()->with('error', 'Hanya pesanan yang dibatalkan yang bisa dikonfirmasi refund-nya.');
        }

        $pesanan->refund_status = 'SELESAI';
        $pesanan->save();

        return back()->with('success', 'Refund pesanan ' . $pesanan->id_pesanan . ' berhasil dikonfirmasi.');
    }

    // =====================================================================
    // SISI ADMIN: Setujui pengajuan pembatalan dari pembeli
    // =====================================================================
    public function setujuiPembatalan($id)
    {
        $pesanan = Pesanan::findOrFail($id);

        if ($pesanan->status !== 'PENGAJUAN BATAL') {
            return back()->with('error', 'Pesanan ini tidak sedang dalam status pengajuan pembatalan.');
        }

        try {
            \DB::transaction(function () use ($pesanan) {
                $items = $pesanan->items_snapshot ?? [];
                foreach ($items as $item) {
                    if (isset($item['produk_variasi_id'])) {
                        $variasi = ProdukVariasi::find($item['produk_variasi_id']);
                        if ($variasi) {
                            $variasi->stok += $item['quantity'];
                            $variasi->save();
                        }
                    }
                }
                $pesanan->status = 'DIBATALKAN';
                $pesanan->refund_status = 'MENUNGGU'; // otomatis tandai perlu refund
                $pesanan->save();
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyetujui pembatalan: ' . $e->getMessage());
        }

        return back()->with('success', 'Pembatalan disetujui. Stok dikembalikan. Refund menunggu konfirmasi admin.');
    }

    // =====================================================================
    // SISI ADMIN: Tolak pengajuan pembatalan
    // =====================================================================
    public function tolakPembatalan($id)
    {
        $pesanan = Pesanan::findOrFail($id);

        if ($pesanan->status !== 'PENGAJUAN BATAL') {
            return back()->with('error', 'Pesanan ini tidak sedang dalam status pengajuan pembatalan.');
        }

        $pesanan->status = 'BELUM KONFIRMASI';
        $pesanan->save();

        return back()->with('success', 'Pengajuan pembatalan ditolak, pesanan kembali ke antrean konfirmasi.');
    }

    // =====================================================================
    // SISI ADMIN: Batalkan dari halaman konfirmasi (Belum Konfirmasi)
    // =====================================================================
    public function batalkanOlehAdmin($id)
    {
        $pesanan = Pesanan::findOrFail($id);

        if ($pesanan->status !== 'BELUM KONFIRMASI') {
            return back()->with('error', 'Pesanan ini sudah diproses, tidak dapat dibatalkan dari halaman ini.');
        }

        try {
            \DB::transaction(function () use ($pesanan) {
                $items = $pesanan->items_snapshot ?? [];
                foreach ($items as $item) {
                    if (isset($item['produk_variasi_id'])) {
                        $variasi = ProdukVariasi::find($item['produk_variasi_id']);
                        if ($variasi) {
                            $variasi->stok += $item['quantity'];
                            $variasi->save();
                        }
                    }
                }
                $pesanan->status = 'DIBATALKAN';
                // Pesanan belum konfirmasi = belum bayar, tidak perlu refund
                $pesanan->refund_status = null;
                $pesanan->save();
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membatalkan pesanan: ' . $e->getMessage());
        }

        return back()->with('success', 'Pesanan ' . $pesanan->id_pesanan . ' berhasil dibatalkan dan stok telah dikembalikan.');
    }

    // =====================================================================
    // Update status pesanan
    // =====================================================================
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:BELUM KONFIRMASI,SUDAH KONFIRMASI,SEDANG DIPROSES,DALAM PERJALANAN,DIBATALKAN,SELESAI,PENGAJUAN BATAL'
        ]);

        $pesanan = Pesanan::findOrFail($id);
        $pesanan->status = $request->status;
        $pesanan->save();

        return back()->with('success', 'Status pesanan ' . $pesanan->id_pesanan . ' berhasil diperbarui!');
    }

    public function tambahItem($id)
    {
        $pesanan = Pesanan::where('id_pesanan', $id)->firstOrFail();
        $produk = Produk::all();
        return view('admin.tambah_item_pesanan', compact('pesanan', 'produk'));
    }

    public function updatePesanan(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $totalBaru = $pesanan->total + ($request->harga_tambahan ?? 0) + ($request->ongkir ?? 0);
        $pesanan->update([
            'nama_barang' => $pesanan->nama_barang . ', ' . $request->barang_baru,
            'total' => $totalBaru,
        ]);
        return redirect('/admin/pesanan-masuk')->with('success', 'Pesanan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Pesanan::destroy($id);
        return redirect()->back()->with('success', 'Pesanan berhasil dihapus!');
    }

    public function statusPesanan(Request $request)
    {
        $email = $request->query('email');
        $kode  = $request->query('kode');
        $pesanan = Pesanan::where('email', $email)->where('id_pesanan', $kode)->first();
        return view('pembeli.status', compact('pesanan'));
    }

    public function uploadBukti(Request $request)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'id_pesanan'       => 'required',
        ]);
        $pesanan = Pesanan::where('id_pesanan', $request->id_pesanan)->firstOrFail();
        $namaFile = time() . '_bukti.' . $request->bukti_pembayaran->extension();
        $request->bukti_pembayaran->move(public_path('images/bukti'), $namaFile);
        $pesanan->bukti_pembayaran = $namaFile;
        $pesanan->save();
        return redirect('/berhasil')->with('success', 'Bukti pembayaran berhasil dikirim!');
    }

    public function konfirmasiDiterima($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->status = 'SELESAI';
        $pesanan->save();
        return redirect()->back()->with('success', 'Pesanan telah dikonfirmasi diterima.');
    }
}