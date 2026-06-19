<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
use App\Models\ProdukVariasi;

class PesananController extends Controller
{   
    /**
     * SISI ADMIN: Melihat history dari seluruh transaksi tanpa filter status.
     */
    public function lihatSemua(Request $request)
    {
        $search = $request->query('search');

        // 🔥 Ditambahkan dengan eager loading 'user'
        $semua_pesanan = Pesanan::with('user')
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('id_pesanan', 'LIKE', "%{$search}%")
                      ->orWhere('nama_pembeli', 'LIKE', "%{$search}%")
                      ->orWhere('status', 'LIKE', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // 🔥 Sinkronisasi nomor HP dari tabel user berdasarkan email
        $semua_pesanan->transform(function ($pesanan) {
            if ($pesanan->user) {
                $pesanan->no_hp = $pesanan->user->no_hp ?? $pesanan->user->telepon ?? $pesanan->user->no_telp;
            } else {
                $userAsli = User::where('email', $pesanan->email)->first();
                $pesanan->no_hp = $userAsli ? ($userAsli->no_hp ?? $userAsli->telepon ?? $userAsli->no_telp) : null;
            }
            return $pesanan;
        });

        return view('admin.lihat_semua', compact('semua_pesanan'));
    }

    /**
     * SISI ADMIN: Mengelola Pesanan Masuk (Sudah Konfirmasi & Siap Diproses/Kirim).
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        // 🔥 Ditambahkan dengan eager loading 'user'
        $pesanan_masuk = Pesanan::with('user')
            ->whereIn('status', ['SEDANG DIPROSES', 'DALAM PERJALANAN'])
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('id_pesanan', 'LIKE', "%{$search}%")
                      ->orWhere('nama_pembeli', 'LIKE', "%{$search}%")
                      ->orWhere('status', 'LIKE', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();
                    
        // 🔥 Sinkronisasi nomor HP dari tabel user berdasarkan email
        $pesanan_masuk->transform(function ($pesanan) {
            if ($pesanan->user) {
                $pesanan->no_hp = $pesanan->user->no_hp ?? $pesanan->user->telepon ?? $pesanan->user->no_telp;
            } else {
                $userAsli = User::where('email', $pesanan->email)->first();
                $pesanan->no_hp = $userAsli ? ($userAsli->no_hp ?? $userAsli->telepon ?? $userAsli->no_telp) : null;
            }
            return $pesanan;
        });

        return view('admin.pesanan_masuk', compact('pesanan_masuk'));
    }

    /**
     * SISI ADMIN: Halaman Konfirmasi Pesanan.
     */
    public function konfirmasi(Request $request)
    {
        $search = $request->query('search');

        // 🔥 Ditambahkan with('user') biar data alamat dari sisi pembeli bisa ketarik!
        $pesanan_konfirmasi = Pesanan::with('user')
            ->where('status', 'BELUM KONFIRMASI')
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('id_pesanan', 'LIKE', "%{$search}%")
                      ->orWhere('nama_pembeli', 'LIKE', "%{$search}%")
                      ->orWhere('total', 'LIKE', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // 🔥 Sinkronisasi nomor HP dari tabel user berdasarkan email pembeli biar link WhatsApp-nya beneran aktif
$pesanan_konfirmasi->transform(fn ($pesanan) => $this->syncNoHp($pesanan));
                    
        return view('admin.pesanan_konfirmasi', compact('pesanan_konfirmasi'));
    }

    /**
     * SISI ADMIN: Melihat data pesanan yang telah sukses diselesaikan.
     */
    public function selesai(Request $request)
    {
        $search = $request->query('search');

        // 🔥 Ditambahkan dengan eager loading 'user' juga biar aman
        $pesanan_selesai = Pesanan::with('user')
            ->where('status', 'SELESAI')
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('id_pesanan', 'LIKE', "%{$search}%")
                      ->orWhere('nama_pembeli', 'LIKE', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // 🔥 Sinkronisasi nomor HP dari tabel user berdasarkan email
        $pesanan_selesai->transform(function ($pesanan) {
            if ($pesanan->user) {
                $pesanan->no_hp = $pesanan->user->no_hp ?? $pesanan->user->telepon ?? $pesanan->user->no_telp;
            } else {
                $userAsli = User::where('email', $pesanan->email)->first();
                $pesanan->no_hp = $userAsli ? ($userAsli->no_hp ?? $userAsli->telepon ?? $userAsli->no_telp) : null;
            }
            return $pesanan;
        });

        return view('admin.pesanan_selesai', compact('pesanan_selesai'));
    }

    private function syncNoHp($pesanan)
{
    // Kalau no_hp di tabel pesanan sudah ada isinya, jangan ditimpa!
    if (!empty($pesanan->no_hp)) {
        return $pesanan;
    }

    if ($pesanan->user) {
        $pesanan->no_hp = $pesanan->user->no_hp ?? $pesanan->user->telepon ?? $pesanan->user->no_telp;
    } else {
        $userAsli = User::where('email', $pesanan->email)->first();
        $pesanan->no_hp = $userAsli ? ($userAsli->no_hp ?? $userAsli->telepon ?? $userAsli->no_telp) : null;
    }

    return $pesanan;
}

    /**
     * SISI ADMIN: Melihat data pesanan yang dibatalkan/refund.
     */
    public function dibatalkan()
    {
        // 🔥 Ditambahkan dengan eager loading 'user' agar seragam
        $pesanan_batal = Pesanan::with('user')
                                ->where('status', 'DIBATALKAN')
                                ->orderBy('created_at', 'desc')
                                ->get();

        // Mapping nomor HP pembeli tetap dipertahankan
        $pesanan_batal->transform(function ($pesanan) {
            if ($pesanan->user) {
                $pesanan->no_hp = $pesanan->user->no_hp ?? $pesanan->user->telepon ?? $pesanan->user->no_telp;
            } else {
                $userAsli = User::where('email', $pesanan->email)->first();
                $pesanan->no_hp = $userAsli ? ($userAsli->no_hp ?? $userAsli->telepon ?? $userAsli->no_telp) : null;
            }
            return $pesanan;
        });

        // 🔥 SEKARANG MENGGUNAKAN EAGER LOADING 'with(variasis)' AGAR STRUKTUR VARIANT TERBACA SEMPURNA DI BLADE
        $semua_produk = Produk::with('variasis')->get();

        // 🔥 Mengirim variabel ke dalam view admin pesanan_batal
        return view('admin.pesanan_batal', compact('pesanan_batal', 'semua_produk'));
    }

    /**
 * SISI ADMIN: Menampilkan daftar pengajuan pembatalan yang menunggu keputusan.
 */
public function pengajuanBatal()
{
    $pesanan_pengajuan = Pesanan::with('user')
        ->where('status', 'PENGAJUAN BATAL')
        ->orderBy('created_at', 'desc')
        ->get();

    $pesanan_pengajuan->transform(function ($pesanan) {
        if ($pesanan->user) {
            $pesanan->no_hp = $pesanan->user->no_hp ?? $pesanan->user->telepon ?? $pesanan->user->no_telp;
        } else {
            $userAsli = User::where('email', $pesanan->email)->first();
            $pesanan->no_hp = $userAsli ? ($userAsli->no_hp ?? $userAsli->telepon ?? $userAsli->no_telp) : null;
        }
        return $pesanan;
    });

    return view('admin.pesanan_pengajuan_batal', compact('pesanan_pengajuan'));
}

/**
 * SISI ADMIN: Menyetujui pengajuan pembatalan.
 * Stok variasi produk dikembalikan berdasarkan snapshot item saat checkout,
 * bukan dari session, supaya tetap akurat walau dieksekusi di sesi admin.
 */
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
            $pesanan->save();
        });
    } catch (\Exception $e) {
        return back()->with('error', 'Gagal menyetujui pembatalan: ' . $e->getMessage());
    }

    return back()->with('success', 'Pembatalan disetujui, stok produk sudah dikembalikan.');
}

/**
 * SISI ADMIN: Menolak pengajuan pembatalan, pesanan kembali diproses normal.
 */
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
    
    /**
     * Menampilkan formulir modifikasi item pesanan secara manual oleh Admin.
     */
    public function tambahItem($id)
    {
        $pesanan = Pesanan::where('id_pesanan', $id)->firstOrFail();
        $produk = Produk::all();
        return view('admin.tambah_item_pesanan', compact('pesanan', 'produk'));
    }

    /**
     * Memproses pembaruan data item dan total nominal belanja di dalam sebuah pesanan.
     */
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

    /**
     * Menghapus secara permanen data riwayat transaksi transaksi dari sistem.
     */
    public function destroy($id)
    {
        Pesanan::destroy($id);
        return redirect()->back()->with('success', 'Pesanan berhasil dihapus!');
    }

    /**
     * FR-07 (Sisi Admin): Mengubah status pesanan produk via dropdown / tombol aksi.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:BELUM KONFIRMASI,SUDAH KONFIRMASI,SEDANG DIPROSES,DALAM PERJALANAN,DIBATALKAN,SELESAI'
        ]);

        $pesanan = Pesanan::findOrFail($id);
        $pesanan->status = $request->status;
        $pesanan->save();

        return back()->with('success', 'Status pesanan ' . $pesanan->id_pesanan . ' berhasil diperbarui!');
    }

    /**
     * SISI PEMBELI: Menampilkan halaman status/lacak pesanan berdasarkan email dan kode order.
     */
    public function statusPesanan(Request $request)
    {
        $email = $request->query('email');
        $kode = $request->query('kode');

        $pesanan = Pesanan::where('email', $email)
                          ->where('id_pesanan', $kode)
                          ->first();

        return view('pembeli.status', compact('pesanan'));
    }



    public function uploadBukti(Request $request)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'id_pesanan' => 'required',
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

        return redirect()->back()->with('success', 'Terima kasih! Pesanan telah dikonfirmasi diterima.');
    }
}