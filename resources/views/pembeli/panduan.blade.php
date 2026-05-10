@extends('layouts.pembeli')

@section('content')
<div class="py-12 px-2">

    {{-- Header --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <span class="text-[10px] uppercase tracking-[0.5em] text-gray-400 mb-2 block">Panduan</span>
            <h1 class="text-2xl font-bold text-[#001f3f] uppercase tracking-[0.2em]">Panduan Pemesanan</h1>
            <p class="text-sm text-gray-400 mt-3">Ikuti langkah-langkah berikut untuk melakukan pemesanan dengan mudah di Kriya Rajut.</p>
        </div>
        <div class="h-[1px] flex-grow hidden md:block bg-gray-200 mb-2 ml-4"></div>
    </div>
    

    {{-- Stepper --}}
    <div class="flex items-center mb-16 overflow-x-auto pb-2 gap-0">
        @php
        $steps = [
            '1' => 'Pilih Produk',
            '2' => 'Lihat Detail & Tambah Keranjang',
            '3' => 'Buka Keranjang & Checkout',
            '4' => 'Isi Data Pengiriman',
            '5' => 'Lakukan Pembayaran',
            '6' => 'Pesanan Berhasil',
        ];
        @endphp

        @foreach($steps as $num => $label)
            <div class="flex items-center flex-shrink-0">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full border border-[#001f3f] flex items-center justify-center text-[10px] font-bold text-[#001f3f] flex-shrink-0">
                        {{ $num }}
                    </div>
                    <span class="text-[10px] uppercase tracking-[0.1em] text-gray-400 whitespace-nowrap">{{ $label }}</span>
                </div>
                @if((int)$num < 6)
                    <div class="w-6 h-px bg-gray-200 mx-2 flex-shrink-0"></div>
                @endif
            </div>
        @endforeach
    </div>

    {{-- Steps --}}
    <div class="space-y-0">

        {{-- Step 1 --}}
        <div class="flex gap-8 py-10 border-t border-gray-100">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 rounded-full border border-[#001f3f] flex items-center justify-center text-sm font-bold text-[#001f3f]">1</div>
            </div>
            <div class="flex-1 pt-1.5">
                <h2 class="text-sm font-bold text-[#001f3f] uppercase tracking-[0.15em] mb-2">Pilih Produk dari Katalog</h2>
                <p class="text-sm text-gray-400 leading-relaxed mb-6">Buka halaman <span class="font-semibold text-[#001f3f]">Home</span> atau <span class="font-semibold text-[#001f3f]">Katalog</span> untuk menjelajahi produk rajutan kami. Gunakan filter kategori di bagian atas untuk mempersempit pencarian. Klik "Lihat Detail" pada produk yang diminati.</p>
                <img src="{{ asset('images/panduan/step1-katalog.jpg') }}" 
                     alt="Screenshot Halaman Katalog"
                     class="w-full rounded-xl border border-gray-200 object-cover">                
            </div>
        </div>

        {{-- Step 2 --}}
        <div class="flex gap-8 py-10 border-t border-gray-100">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 rounded-full border border-[#001f3f] flex items-center justify-center text-sm font-bold text-[#001f3f]">2</div>
            </div>
            <div class="flex-1 pt-1.5">
                <h2 class="text-sm font-bold text-[#001f3f] uppercase tracking-[0.15em] mb-2">Lihat Detail & Tambah ke Keranjang</h2>
                <p class="text-sm text-gray-400 leading-relaxed mb-6">Di halaman detail produk, periksa nama produk, harga, deskripsi, dan stok tersedia. Atur jumlah yang diinginkan lalu klik tombol <span class="font-semibold text-[#001f3f]">"+ Tambah ke Keranjang"</span>.</p>
                <img src="{{ asset('images/panduan/step2-detail.jpg') }}" 
                     alt="Screenshot Halaman Detail Produk"
                     class="w-full rounded-xl border border-gray-200 object-cover">
            </div>
        </div>

        {{-- Step 3 --}}
        <div class="flex gap-8 py-10 border-t border-gray-100">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 rounded-full border border-[#001f3f] flex items-center justify-center text-sm font-bold text-[#001f3f]">3</div>
            </div>
            <div class="flex-1 pt-1.5">
                <h2 class="text-sm font-bold text-[#001f3f] uppercase tracking-[0.15em] mb-2">Buka Keranjang & Lanjut Checkout</h2>
                <p class="text-sm text-gray-400 leading-relaxed mb-6">Klik ikon keranjang <i class="fa-solid fa-bag-shopping text-xs"></i> di pojok kanan atas navbar. Periksa daftar produk dan jumlah pesanan. Jika sudah sesuai, klik tombol <span class="font-semibold text-[#001f3f]">"Buat Pesanan Sekarang"</span> untuk melanjutkan.</p>
                <img src="{{ asset('images/panduan/step3-keranjang.jpg') }}" 
                     alt="Screenshot Halaman Keranjang"
                     class="w-full rounded-xl border border-gray-200 object-cover">
            </div>
        </div>

        {{-- Step 4 --}}
        <div class="flex gap-8 py-10 border-t border-gray-100">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 rounded-full border border-[#001f3f] flex items-center justify-center text-sm font-bold text-[#001f3f]">4</div>
            </div>
            <div class="flex-1 pt-1.5">
                <h2 class="text-sm font-bold text-[#001f3f] uppercase tracking-[0.15em] mb-2">Isi Data Pengiriman & Buat Pesanan</h2>
                <p class="text-sm text-gray-400 leading-relaxed mb-6">Lengkapi formulir data pengiriman: nama penerima, nomor telepon, email, alamat lengkap, kota tujuan, dan metode pembayaran. Cek ringkasan belanja di sebelah kanan, lalu klik <span class="font-semibold text-[#001f3f]">"Buat Pesanan"</span>.</p>
                <img src="{{ asset('images/panduan/step4-checkout.jpg') }}" 
                     alt="Screenshot Halaman Checkout"
                     class="w-full rounded-xl border border-gray-200 object-cover">
            </div>
        </div>

        {{-- Step 5 --}}
        <div class="flex gap-8 py-10 border-t border-gray-100">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 rounded-full border border-[#001f3f] flex items-center justify-center text-sm font-bold text-[#001f3f]">5</div>
            </div>
            <div class="flex-1 pt-1.5">
                <h2 class="text-sm font-bold text-[#001f3f] uppercase tracking-[0.15em] mb-2">Lakukan Pembayaran</h2>
                <p class="text-sm text-gray-400 leading-relaxed mb-6">Setelah pesanan dibuat, kamu akan diarahkan ke halaman <span class="font-semibold text-[#001f3f]">Pembayaran</span>. Transfer sesuai total pembayaran ke nomor rekening yang tertera, lalu unggah bukti transfer (.jpg) dan klik <span class="font-semibold text-[#001f3f]">"Selesai"</span>.</p>
                <img src="{{ asset('images/panduan/step5-pembayaran.jpg') }}" 
                     alt="Screenshot Halaman Pembayaran"
                     class="w-full rounded-xl border border-gray-200 object-cover">
            </div>
        </div>

        {{-- Step 6 --}}
        <div class="flex gap-8 py-10 border-t border-gray-100">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 rounded-full border border-[#001f3f] flex items-center justify-center text-sm font-bold text-[#001f3f]">6</div>
            </div>
            <div class="flex-1 pt-1.5">
                <h2 class="text-sm font-bold text-[#001f3f] uppercase tracking-[0.15em] mb-2">Pesanan Berhasil Dibuat</h2>
                <p class="text-sm text-gray-400 leading-relaxed mb-6">Pesananmu sudah kami terima! Jangan lupa untuk mengunduh bukti pemesanan dengan cara mengklik tombol. <span class="font-semibold text-[#001f3f]">"Unduh Bukti Pemesanan"</span> dan Pantau status pesanan kapan saja melalui menu <span class="font-semibold text-[#001f3f]">"Lacak Pesanan"</span> di navbar dengan menggunakan nomor pesanan yang diberikan.</p>
                <img src="{{ asset('images/panduan/step6-pembayaranberhasil.jpg') }}" 
                     alt="Screenshot Halaman Status Pesanan"
                     class="w-full rounded-xl border border-gray-200 object-cover">
                <p class="text-sm text-gray-400 leading-relaxed mb-6 pt-10"><span class="font-semibold text-[#001f3f]">Bukti pesananmu</span>  akan terlihat seperti ini. <span class="font-semibold text-[#001f3f]">Kode pesanan akan dibutuhkan</span> saat kamu ingin melacak pesananmu.</p>
                <img src="{{ asset('images/panduan/step6-bukti.jpg') }}" 
                     alt="Screenshot Halaman Status Pesanan"
                     class="w-full rounded-xl border border-gray-200 object-cover">
            </div>
        </div>

        {{-- Step 7 --}}
        <div class="flex gap-8 py-10 border-t border-gray-100">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 rounded-full border border-[#001f3f] flex items-center justify-center text-sm font-bold text-[#001f3f]">7</div>
            </div>
            <div class="flex-1 pt-1.5">
                <h2 class="text-sm font-bold text-[#001f3f] uppercase tracking-[0.15em] mb-2">Lacak Pesanan</h2>
                <p class="text-sm text-gray-400 leading-relaxed mb-6">Saat kamu mengeklik tombol <span class="font-semibold text-[#001f3f]">Lacak Pesanan"</span>, kamu akan diminta untuk memasukkan alamat emailmu dan kode pesanan yang telah kamu terima.</p>
                <img src="{{ asset('images/panduan/step7-loginlacak.jpg') }}" 
                     alt="Screenshot Halaman Login Lacak Pesanan"
                     class="w-full rounded-xl border border-gray-200 object-cover">
                <p class="text-sm text-gray-400 leading-relaxed mb-6 pt-10">Setelah itu, kamu dapat melihat perkembangan status pesananmu melalui halaman ini..</p>
                <img src="{{ asset('images/panduan/step7-statuspesanan.jpg') }}" 
                     alt="Screenshot Halaman Lacak Pesanan"
                     class="w-full rounded-xl border border-gray-200 object-cover">
            </div>
        </div>

    </div>

    {{-- CTA --}}
    <div class="border-t border-gray-100 pt-14 mt-4">
        <div class=" px-8 py-10 text-center">
            <p class="text-[10px] uppercase tracking-[0.25em] text-gray-400 mb-6">Masih ada pertanyaan?</p>
            <div class="flex justify-center gap-3 flex-wrap">
                <a href="/FAQ"
                    class="inline-flex items-center gap-2 bg-white hover:bg-[#F3F5F1] text-[#001f3f] border border-gray-200 font-bold uppercase tracking-[0.2em] text-[11px] px-8 py-3.5 rounded-full transition-all duration-200">
                    Lihat FAQ
                </a>
                <a href="https://wa.me/6285778092881" target="_blank"
                    class="inline-flex items-center gap-2 bg-[#001f3f] hover:bg-[#003366] text-white font-bold uppercase tracking-[0.2em] text-[11px] px-8 py-3.5 rounded-full transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fab fa-whatsapp text-sm"></i> Chat WhatsApp
                </a>
            </div>
        </div>
    </div>

</div>
@endsection