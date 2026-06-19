@extends('layouts.pembeli')

@section('content')
<div class="py-10 px-2">

    <div class="mb-6 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <span class="text-[11px] uppercase tracking-[0.5em] text-gray-400 mb-2 block">Panduan</span>
            <h1 class="text-2xl font-bold text-[#001f3f] uppercase tracking-[0.2em]">Panduan Pemesanan</h1>
            <p class="text-sm text-gray-500 mt-3">Ikuti langkah-langkah berikut untuk melakukan pemesanan dengan mudah di Kriya Rajut.</p>
        </div>
        <div class="h-[1px] flex-grow hidden md:block bg-gray-200 mb-2 ml-4"></div>
    </div>

    {{-- Stepper --}}
    <div class="flex items-center mb-14 overflow-x-auto pb-2 gap-0">
        @php
        $steps = [
            '1' => 'Pilih Produk',
            '2' => 'Detail & Keranjang',
            '3' => 'Keranjang & Checkout',
            '4' => 'Isi Data Pengiriman',
            '5' => 'Lakukan Pembayaran',
            '6' => 'Pesanan Berhasil',
        ];
        @endphp
        @foreach($steps as $num => $label)
            <div class="flex items-center flex-shrink-0">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full border border-[#001f3f] flex items-center justify-center text-[11px] font-bold text-[#001f3f] flex-shrink-0">{{ $num }}</div>
                    <span class="text-[11px] uppercase tracking-[0.1em] text-gray-400 whitespace-nowrap">{{ $label }}</span>
                </div>
                @if((int)$num < 6)
                    <div class="w-5 h-px bg-gray-200 mx-2 flex-shrink-0"></div>
                @endif
            </div>
        @endforeach
    </div>

    {{-- Steps --}}
    <div class="space-y-0">

        @php
        $panduan_steps = [
            ['num' => 1, 'title' => 'Pilih Produk dari Katalog', 'desc' => 'Buka halaman <strong class="text-[#001f3f]">Home</strong> atau <strong class="text-[#001f3f]">Katalog</strong> untuk menjelajahi produk rajutan kami. Gunakan filter kategori di bagian atas untuk mempersempit pencarian. Klik "Lihat Detail" pada produk yang diminati.', 'img' => 'panduan/step1-katalog.jpg', 'alt' => 'Screenshot Halaman Katalog'],
            ['num' => 2, 'title' => 'Lihat Detail & Tambah ke Keranjang', 'desc' => 'Di halaman detail produk, periksa nama produk, harga, deskripsi, dan stok tersedia. Atur jumlah yang diinginkan lalu klik tombol <strong class="text-[#001f3f]">"+ Tambah ke Keranjang"</strong>.', 'img' => 'panduan/step2-detail.jpg', 'alt' => 'Screenshot Halaman Detail Produk'],
            ['num' => 3, 'title' => 'Buka Keranjang & Lanjut Checkout', 'desc' => 'Klik ikon keranjang <i class="fa-solid fa-bag-shopping text-xs"></i> di pojok kanan atas navbar. Periksa daftar produk dan jumlah pesanan. Jika sudah sesuai, klik tombol <strong class="text-[#001f3f]">"Buat Pesanan Sekarang"</strong> untuk melanjutkan.', 'img' => 'panduan/step3-keranjang.jpg', 'alt' => 'Screenshot Halaman Keranjang'],
            ['num' => 4, 'title' => 'Isi Data Pengiriman & Buat Pesanan', 'desc' => 'Lengkapi formulir data pengiriman: nama penerima, nomor telepon, email, alamat lengkap, dan metode pembayaran. Cek ringkasan belanja di sebelah kanan, lalu klik <strong class="text-[#001f3f]">"Lanjut ke Pembayaran"</strong>.', 'img' => 'panduan/step4-checkout.jpg', 'alt' => 'Screenshot Halaman Checkout'],
            ['num' => 5, 'title' => 'Lakukan Pembayaran', 'desc' => 'Setelah pesanan dibuat, kamu akan diarahkan ke halaman <strong class="text-[#001f3f]">Pembayaran</strong>. Transfer sesuai total pembayaran ke nomor rekening yang tertera (atau scan QRIS), lalu unggah bukti transfer dan klik <strong class="text-[#001f3f]">"Selesai & Kirim Bukti"</strong>.', 'img' => 'panduan/step5-pembayaran.jpg', 'alt' => 'Screenshot Halaman Pembayaran'],
        ];
        @endphp

        @foreach($panduan_steps as $step)
        <div class="flex gap-6 sm:gap-8 py-10 border-t border-gray-100">
            <div class="flex-shrink-0">
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full border border-[#001f3f] flex items-center justify-center text-sm font-bold text-[#001f3f]">{{ $step['num'] }}</div>
            </div>
            <div class="flex-1 pt-1">
                <h2 class="text-sm font-bold text-[#001f3f] uppercase tracking-[0.15em] mb-3">{{ $step['title'] }}</h2>
                <p class="text-sm text-gray-500 leading-relaxed mb-5">{!! $step['desc'] !!}</p>
                <img src="{{ asset('images/' . $step['img']) }}" alt="{{ $step['alt'] }}" class="w-full rounded-xl border border-gray-200 object-cover">
            </div>
        </div>
        @endforeach

        {{-- Step 6 --}}
        <div class="flex gap-6 sm:gap-8 py-10 border-t border-gray-100">
            <div class="flex-shrink-0">
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full border border-[#001f3f] flex items-center justify-center text-sm font-bold text-[#001f3f]">6</div>
            </div>
            <div class="flex-1 pt-1">
                <h2 class="text-sm font-bold text-[#001f3f] uppercase tracking-[0.15em] mb-3">Pesanan Berhasil Dibuat</h2>
                <p class="text-sm text-gray-500 leading-relaxed mb-5">Pesananmu sudah kami terima! Jangan lupa untuk mengunduh bukti pemesanan dengan klik tombol <strong class="text-[#001f3f]">"Unduh Bukti Pemesanan"</strong> dan pantau status pesanan kapan saja melalui menu <strong class="text-[#001f3f]">"Lacak Pesanan"</strong> di navbar.</p>
                <img src="{{ asset('images/panduan/step6-pembayaranberhasil.jpg') }}" alt="Screenshot Halaman Status Pesanan" class="w-full rounded-xl border border-gray-200 object-cover mb-5">
                <p class="text-sm text-gray-500 leading-relaxed mb-5"><strong class="text-[#001f3f]">Bukti pesananmu</strong> akan terlihat seperti ini. <strong class="text-[#001f3f]">Kode pesanan akan dibutuhkan</strong> saat kamu ingin melacak pesananmu.</p>
                <img src="{{ asset('images/panduan/step6-bukti.jpg') }}" alt="Screenshot Bukti Pesanan" class="w-full rounded-xl border border-gray-200 object-cover">
            </div>
        </div>

        {{-- Step 7 --}}
        <div class="flex gap-6 sm:gap-8 py-10 border-t border-gray-100">
            <div class="flex-shrink-0">
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full border border-[#001f3f] flex items-center justify-center text-sm font-bold text-[#001f3f]">7</div>
            </div>
            <div class="flex-1 pt-1">
                <h2 class="text-sm font-bold text-[#001f3f] uppercase tracking-[0.15em] mb-3">Lacak Pesanan</h2>
                <p class="text-sm text-gray-500 leading-relaxed mb-5">Saat kamu mengeklik tombol <strong class="text-[#001f3f]">"Lacak Pesanan"</strong>, kamu akan diminta untuk memasukkan alamat emailmu dan kode pesanan yang telah kamu terima.</p>
                <img src="{{ asset('images/panduan/step7-loginlacak.jpg') }}" alt="Screenshot Halaman Login Lacak Pesanan" class="w-full rounded-xl border border-gray-200 object-cover mb-5">
                <p class="text-sm text-gray-500 leading-relaxed mb-5">Setelah itu, kamu dapat melihat perkembangan status pesananmu melalui halaman ini.</p>
                <img src="{{ asset('images/panduan/step7-statuspesanan.jpg') }}" alt="Screenshot Halaman Lacak Pesanan" class="w-full rounded-xl border border-gray-200 object-cover">
            </div>
        </div>

    </div>

    {{-- CTA --}}
    <div class="border-t border-gray-100 pt-12 mt-4">
        <div class="px-4 py-10 text-center">
            <p class="text-xs uppercase tracking-[0.25em] text-gray-400 mb-5">Masih ada pertanyaan?</p>
            <div class="flex justify-center gap-3 flex-wrap">
                <a href="/FAQ"
                    class="inline-flex items-center gap-2 bg-white hover:bg-[#F3F5F1] text-[#001f3f] border border-gray-200 font-bold uppercase tracking-[0.2em] text-xs px-8 py-3.5 rounded-full transition-all duration-200">
                    Lihat FAQ
                </a>
                @php
                    $pesanWaPanduan = 'Halo admin Kriya Rajut! Saya sudah baca panduan pemesanannya tapi masih ada yang ingin saya tanyakan. Boleh dibantu?';
                @endphp
                <a href="https://wa.me/6285778092881?text={{ urlencode($pesanWaPanduan) }}" target="_blank"
                    class="inline-flex items-center gap-2 bg-[#001f3f] hover:bg-[#003366] text-white font-bold uppercase tracking-[0.2em] text-xs px-8 py-3.5 rounded-full transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fab fa-whatsapp text-sm"></i> Chat WhatsApp
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
