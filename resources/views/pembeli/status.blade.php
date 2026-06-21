@extends('layouts.pembeli')

@section('content')
<div class="py-10 max-w-7xl mx-auto px-4">
    <div class="mb-8">
        <span class="text-xs tracking-[0.5em] text-gray-400 mb-2 block uppercase">Pesananmu</span>
        <h1 class="text-2xl font-bold text-[#001f3f] tracking-[0.2em] uppercase">Status Pesanan</h1>
        <div class="mt-3 h-px w-12 bg-[#001f3f]"></div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 text-xs font-bold uppercase tracking-widest rounded-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 text-xs font-bold uppercase tracking-widest rounded-sm">{{ session('error') }}</div>
    @endif

    <div class="space-y-4">
        @if(isset($pesanan) && $pesanan)
<div class="w-full bg-white border border-gray-100 shadow-sm hover:shadow-md transition-all overflow-hidden rounded-sm">
    
            {{-- Header: ID & Status --}}
            <div class="px-6 pt-5 pb-4 border-b border-gray-50 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <p class="text-xs text-gray-400 font-mono uppercase tracking-widest">ID: {{ $pesanan->id_pesanan }}</p>
                <div class="flex items-center gap-2">
                    @if($pesanan->status === 'BELUM KONFIRMASI')
                        <div class="h-1.5 w-1.5 rounded-full bg-gray-400 animate-pulse"></div>
                        <p class="text-xs font-bold text-gray-500 italic tracking-widest uppercase">Menunggu Konfirmasi</p>
                    @elseif($pesanan->status === 'SEDANG DIPROSES')
                        <div class="h-1.5 w-1.5 rounded-full bg-amber-400 animate-pulse"></div>
                        <p class="text-xs font-bold text-amber-500 italic tracking-widest uppercase">Sedang Diproses</p>
                    @elseif($pesanan->status === 'DALAM PERJALANAN')
                        <div class="h-1.5 w-1.5 rounded-full bg-blue-400 animate-pulse"></div>
                        <p class="text-xs font-bold text-blue-500 italic tracking-widest uppercase">Dalam Perjalanan</p>
                    @elseif($pesanan->status === 'SELESAI')
                        <div class="h-1.5 w-1.5 rounded-full bg-emerald-500"></div>
                        <p class="text-xs font-bold text-emerald-500 italic tracking-widest uppercase">Pesanan Selesai</p>
                    @elseif($pesanan->status === 'DIBATALKAN')
                        <div class="h-1.5 w-1.5 rounded-full bg-red-500"></div>
                        <p class="text-xs font-bold text-red-500 italic tracking-widest uppercase">Pesanan Dibatalkan</p>
                    @elseif($pesanan->status === 'PENGAJUAN BATAL')
                        <div class="h-1.5 w-1.5 rounded-full bg-orange-400 animate-pulse"></div>
                        <p class="text-xs font-bold text-orange-500 italic tracking-widest uppercase">Menunggu Konfirmasi Pembatalan</p>
                    @else
                        <div class="h-1.5 w-1.5 rounded-full bg-blue-500"></div>
                        <p class="text-xs font-bold text-blue-500 italic tracking-widest uppercase">{{ $pesanan->status }}</p>
                    @endif
                </div>
            </div>

            {{-- List Item Produk --}}
            <div class="p-6 space-y-4">
                @forelse(($pesanan->items_snapshot ?? []) as $item)
                <div class="flex gap-4 items-center">
                    <div class="w-16 h-16 bg-gray-50 border border-gray-100 flex-shrink-0 flex items-center justify-center overflow-hidden rounded-lg">
                        @if(!empty($item['foto']))
                            <img src="{{ asset('images/' . $item['foto']) }}" class="w-full h-full object-cover" alt="{{ $item['nama'] }}">
                        @else
                            <i class="fa-solid fa-box text-xl text-gray-300"></i>
                        @endif
                    </div>
                    <div class="flex-grow min-w-0">
                        <h3 class="font-bold text-xs text-[#001f3f] uppercase tracking-wide leading-snug">{{ $item['nama'] }}</h3>
                        <p class="text-10px text-gray-400 mt-0.5">IDR {{ number_format($item['harga'], 0, ',', '.') }} × {{ $item['quantity'] }}</p>
                        <button type="button"
                            class="btn-lihat-detail mt-1 text-[11px] tracking-widest font-bold text-[#001f3f] hover:underline block text-left"
                            data-nama="{{ $item['nama'] }}"
                            data-harga="{{ number_format($item['harga'], 0, ',', '.') }}"
                            data-deskripsi="{{ $item['deskripsi'] ?? 'Tidak ada deskripsi.' }}"
                            data-foto="{{ !empty($item['foto']) ? asset('images/' . $item['foto']) : '' }}">
                            Lihat Detail
                        </button>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest">Subtotal</p>
                        <p class="font-bold text-[#001f3f] text-10px">Rp {{ number_format($item['harga'] * $item['quantity'], 0, ',', '.') }}</p>
                    </div>
                </div>
                @empty
                <div class="flex gap-4 items-center">
                    <div class="w-16 h-16 bg-gray-50 border border-gray-100 flex-shrink-0 flex items-center justify-center overflow-hidden rounded-lg">
                        <i class="fa-solid fa-box text-xl text-gray-300"></i>
                    </div>
                    <h3 class="font-bold text-xs text-[#001f3f] uppercase tracking-wide">{{ $pesanan->nama_barang }}</h3>
                </div>
                @endforelse
            </div>

            {{-- Total & Tombol Aksi --}}
            <div class="px-6 pb-6 pt-4 border-t border-gray-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <p class="text-xs text-gray-400 uppercase tracking-widest">
                    Total Bayar <span class="font-bold text-[#001f3f] text-sm ml-1">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</span>
                </p>
                <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                    {{-- Tombol WA: Pesan disesuaikan konteks status pesanan --}}
                    @php
                        $noWa = '6285778092881';
                        $pesanWA = 'Halo admin Kriya Rajut, saya ingin menanyakan terkait status pesanan saya dengan kode ' . $pesanan->id_pesanan . '. Mohon Konfirmasinya ya.';
                        if ($pesanan->status === 'BELUM KONFIRMASI') {
                            $pesanWA = 'Halo admin Kriya Rajut, pesanan saya dengan kode ' . $pesanan->id_pesanan . ' sudah masuk ya. Mohon dikonfirmasi pembayarannya.';
                        } elseif ($pesanan->status === 'DALAM PERJALANAN') {
                            $pesanWA = 'Halo admin Kriya Rajut, pesanan saya (' . $pesanan->id_pesanan . ') sudah dalam perjalanan. Boleh minta info resi pengirimannya?';
                        } elseif ($pesanan->status === 'SELESAI') {
                            $pesanWA = 'Halo admin Kriya Rajut, pesanan saya (' . $pesanan->id_pesanan . ') sudah saya terima. Terima kasih!';
                        }
                    @endphp
                    <a href="https://wa.me/{{ $noWa }}?text={{ urlencode($pesanWA) }}" target="_blank"
                        class="flex items-center justify-center gap-2 border border-gray-200 px-6 py-3 rounded-lg text-xs font-bold uppercase tracking-widest text-gray-600 hover:bg-gray-50 transition-all w-full md:w-auto text-center">
                        <i class="fa-brands fa-whatsapp text-sm text-emerald-500"></i>
                        Kontak Penjual
                    </a>

                    @if($pesanan->status === 'BELUM KONFIRMASI')
                        <button type="button" onclick="konfirmasiPembatalan('{{ $pesanan->id_pesanan }}')"
                            class="flex items-center justify-center gap-2 border border-red-200 bg-red-50 hover:bg-red-100 px-6 py-3 rounded-lg text-xs font-bold uppercase tracking-widest text-red-600 transition-all w-full md:w-auto text-center cursor-pointer">
                            <i class="fa-solid fa-xmark text-xs"></i> Ajukan Pembatalan
                        </button>
                    @elseif($pesanan->status === 'PENGAJUAN BATAL')
                        <div class="flex items-center justify-center gap-2 border border-orange-200 bg-orange-50 px-6 py-3 rounded-lg text-xs font-bold uppercase tracking-widest text-orange-600 w-full md:w-auto text-center">
                            <i class="fa-solid fa-clock text-xs"></i> Menunggu Admin
                        </div>
                    @elseif($pesanan->status === 'DALAM PERJALANAN')
                        <form action="{{ route('pesanan.konfirmasi', $pesanan->id) }}" method="POST"
                            onsubmit="return confirm('Konfirmasi pesanan sudah diterima? Tindakan ini tidak bisa dibatalkan.')">
                            @csrf
                            <button type="submit"
                                class="flex items-center justify-center gap-2 border border-emerald-200 bg-emerald-50 hover:bg-emerald-100 px-6 py-3 rounded-lg text-xs font-bold uppercase tracking-widest text-emerald-600 transition-all w-full md:w-auto text-center cursor-pointer">
                                <i class="fa-solid fa-circle-check text-xs"></i> Konfirmasi Diterima
                            </button>
                        </form>
                    @elseif($pesanan->status === 'SELESAI')
                        <div class="flex items-center justify-center gap-2 border border-emerald-200 bg-emerald-50 px-6 py-3 rounded-lg text-xs font-bold uppercase tracking-widest text-emerald-600 w-full md:w-auto text-center">
                            <i class="fa-solid fa-circle-check text-xs"></i> Pesanan Diterima
                        </div>
                        <button type="button" data-modal-target="modal-komplain" data-modal-toggle="modal-komplain"
                            class="flex items-center justify-center gap-2 border border-orange-200 bg-orange-50 hover:bg-orange-100 px-6 py-3 rounded-lg text-xs font-bold uppercase tracking-widest text-orange-600 transition-all w-full md:w-auto text-center cursor-pointer">
                            <i class="fa-solid fa-rotate-left text-xs"></i> Ajukan Retur / Komplain
                        </button>
                    @endif
                </div>
            </div>
        </div>

        @else
        <div class="text-center py-20 bg-white border border-dashed border-gray-200 rounded-xl">
            <p class="text-gray-400 text-xs tracking-[0.3em]">Belum ada data pesanan untuk dilacak</p>
        </div>
        @endif
    </div>

    <div class="mt-10 text-center">
        <a href="/katalog"
            class="bg-[#001f3f] text-white px-10 py-4 rounded-full text-xs font-bold tracking-[0.2em] shadow-lg hover:bg-gray-800 transition-all inline-flex items-center gap-3">
            <i class="fa-solid fa-bag-shopping"></i> Mulai Belanja
        </a>
    </div>
</div>

{{-- FORM PEMBATALAN --}}
@if(isset($pesanan) && $pesanan)
<form id="form-batal-pesanan" action="{{ route('pembeli.pesanan.ajukanBatal') }}" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="id_pesanan" id="input-batal-id" value="">
</form>
@endif

{{-- MODAL DETAIL --}}
<div id="modal-detail" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-2xl relative shadow-2xl flex flex-col md:flex-row">
        <button onclick="tutupModalDetail()" class="absolute top-4 right-4 text-gray-400 hover:text-black z-10">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
        <div class="w-full md:w-1/2 bg-gray-50 aspect-square flex items-center justify-center overflow-hidden">
            <img id="detail-foto" src="" class="w-full h-full object-cover" alt="Foto Produk">
        </div>
        <div class="p-8 md:w-1/2 flex flex-col justify-center">
            <h2 id="detail-nama" class="text-lg font-bold text-[#001f3f] uppercase tracking-wide mb-2">Nama Produk</h2>
            <p id="detail-harga" class="text-sm font-bold text-gray-400 mb-4">Rp 0</p>
            <p id="detail-deskripsi" class="text-xs text-gray-500 leading-relaxed mb-6">Deskripsi...</p>
        </div>
    </div>
</div>

{{-- MODAL KOMPLAIN --}}
@if(isset($pesanan) && $pesanan && $pesanan->status === 'SELESAI')
<div id="modal-komplain" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-lg max-h-full">
        <div class="relative bg-white rounded-xl shadow">
            <div class="flex items-center justify-between p-6 border-b border-gray-100">
                <h3 class="text-xs font-bold text-[#001f3f] uppercase tracking-[0.2em]">Ajukan Retur / Komplain</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" data-modal-hide="modal-komplain">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <form id="form-komplain" onsubmit="kirimKomplain(event)" class="p-6 space-y-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-[0.25em] text-gray-500 mb-2">Jenis Pengajuan</label>
                    <select id="komplain-jenis" required
                        class="w-full border-b-2 border-gray-100 border-t-0 border-l-0 border-r-0 focus:border-[#001f3f] focus:ring-0 text-xs py-3 px-0 font-bold uppercase tracking-widest bg-transparent outline-none">
                        <option value="Retur Barang">Retur Barang</option>
                        <option value="Tukar Barang">Tukar Barang</option>
                        <option value="Komplain Kualitas">Komplain Kualitas</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-[0.25em] text-gray-500 mb-2">Alasan / Penjelasan</label>
                    <textarea id="komplain-alasan" rows="3" required placeholder="Ceritakan kendalanya..."
                        class="w-full border-b-2 border-gray-100 border-t-0 border-l-0 border-r-0 focus:border-[#001f3f] focus:ring-0 text-sm py-3 px-0 bg-transparent outline-none resize-none"></textarea>
                </div>
                <p class="text-xs text-gray-400 italic">Kalau ada foto bukti, lampirkan langsung di chat WhatsApp setelah ini ya.</p>
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 bg-[#25D366] hover:bg-[#1ebe57] text-white font-bold py-3.5 rounded-full uppercase text-xs tracking-[0.25em] transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fa-brands fa-whatsapp"></i> Lanjut ke WhatsApp
                </button>
            </form>
        </div>
    </div>
</div>
@endif

<script>
    document.querySelectorAll('.btn-lihat-detail').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('detail-nama').innerText = this.dataset.nama;
            document.getElementById('detail-harga').innerText = 'IDR ' + this.dataset.harga;
            document.getElementById('detail-deskripsi').innerText = this.dataset.deskripsi;
            document.getElementById('detail-foto').src = this.dataset.foto;
            document.getElementById('modal-detail').classList.remove('hidden');
        });
    });
    function tutupModalDetail() { document.getElementById('modal-detail').classList.add('hidden'); }

    function konfirmasiPembatalan(idPesanan) {
        if (confirm("Sebelum mengajukan pembatalan, pastikan kamu sudah menghubungi admin via WhatsApp untuk berdiskusi terlebih dahulu.\n\nLanjutkan ajukan pembatalan untuk pesanan " + idPesanan + "?")) {
            document.getElementById('input-batal-id').value = idPesanan;
            document.getElementById('form-batal-pesanan').submit();
        }
    }

    function kirimKomplain(e) {
        e.preventDefault();
        const jenis = document.getElementById('komplain-jenis').value;
        const alasan = document.getElementById('komplain-alasan').value;
        const idPesanan = '{{ $pesanan->id_pesanan ?? "" }}';
        const pesan = `Halo admin Kriya Rajut, saya ingin mengajukan ${jenis} untuk pesanan ${idPesanan}.\n\nAlasan: ${alasan}\n\nMohon bantuannya ya, terima kasih!`;
        window.open('https://wa.me/6285778092881?text=' + encodeURIComponent(pesan), '_blank');
        document.getElementById('modal-komplain').classList.add('hidden');
        document.getElementById('form-komplain').reset();
    }
</script>
@endsection
