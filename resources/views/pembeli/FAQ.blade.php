@extends('layouts.pembeli')

@section('content')
<div class="py-10 px-2">

    <div class="mb-6 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <span class="text-[11px] uppercase tracking-[0.5em] text-gray-400 mb-2 block">Bantuan</span>
            <h1 class="text-2xl font-bold text-[#001f3f] uppercase tracking-[0.2em]">FAQ</h1>
            <p class="text-sm text-gray-500 mt-3">Temukan jawaban atas pertanyaan umum seputar belanja di Kriya Rajut.</p>
        </div>
        <div class="h-[1px] flex-grow hidden md:block bg-gray-200 mb-2 ml-4"></div>
    </div>

    <div class="flex gap-10">
        {{-- Sidebar --}}
        <div class="hidden md:block w-44 flex-shrink-0">
            <p class="text-[11px] font-bold uppercase tracking-[0.25em] text-gray-400 mb-4">Kategori</p>
            <ul class="space-y-1">
                @foreach([
                    ['id' => 'semua', 'label' => 'Semua'],
                    ['id' => 'pemesanan', 'label' => 'Pemesanan'],
                    ['id' => 'pembayaran', 'label' => 'Pembayaran'],
                    ['id' => 'pengiriman', 'label' => 'Pengiriman'],
                    ['id' => 'produk', 'label' => 'Produk & Retur'],
                ] as $kat)
                <li>
                    <button onclick="filterKategori('{{ $kat['id'] }}')" id="btn-{{ $kat['id'] }}"
                        class="faq-btn w-full text-left px-3 py-2.5 text-xs uppercase tracking-widest transition-all duration-150
                        {{ $kat['id'] === 'semua' ? 'text-[#001f3f] font-bold border-l-2 border-[#001f3f] pl-3 bg-white' : 'text-gray-400 hover:text-[#001f3f] border-l-2 border-transparent' }}">
                        {{ $kat['label'] }}
                    </button>
                </li>
                @endforeach
            </ul>
        </div>

        {{-- Konten FAQ --}}
        <div class="flex-1 min-w-0 space-y-10">

            @foreach([
                ['kategori' => 'pemesanan', 'label' => 'Pemesanan', 'items' => [
                    ['q' => 'Bagaimana cara melakukan pemesanan?', 'a' => 'Pilih produk yang kamu inginkan, tambahkan ke keranjang, lalu ikuti proses checkout. Kamu akan diminta mengisi data pengiriman dan melakukan pembayaran.'],
                    ['q' => 'Apakah saya perlu membuat akun untuk memesan?', 'a' => 'Tidak perlu. Kamu bisa memesan tanpa membuat akun. Cukup masukkan email dan nomor telepon untuk melacak pesananmu.'],
                    ['q' => 'Apakah barang yang sudah dipesan bisa dibatalkan?', 'a' => 'Pembatalan hanya bisa dilakukan sebelum pembayaran dikonfirmasi. Hubungi kami via WhatsApp sesegera mungkin.'],
                    ['q' => 'Berapa batas minimum dan maksimum pemesanan?', 'a' => 'Tidak ada batas minimum atau maksimum. Kamu bisa memesan berapa pun sesuai kebutuhan.'],
                ]],
                ['kategori' => 'pembayaran', 'label' => 'Pembayaran', 'items' => [
                    ['q' => 'Metode pembayaran apa saja yang tersedia?', 'a' => 'Saat ini kami menerima pembayaran melalui Transfer Bank (BCA) dan QRIS. Metode lain akan segera menyusul.'],
                    ['q' => 'Bagaimana cara mengirim bukti pembayaran?', 'a' => 'Setelah transfer, upload foto bukti pembayaran di halaman pembayaran atau kirim langsung via WhatsApp kami.'],
                    ['q' => 'Apa yang terjadi jika pembayaran saya belum dikonfirmasi?', 'a' => 'Konfirmasi biasanya membutuhkan 1×24 jam. Jika lebih dari itu, silakan hubungi kami via WhatsApp dengan menyertakan bukti transfer.'],
                ]],
                ['kategori' => 'pengiriman', 'label' => 'Pengiriman', 'items' => [
                    ['q' => 'Ke mana saja pengiriman bisa dilakukan?', 'a' => 'Saat ini kami melayani pengiriman ke seluruh wilayah Batam via kurir lokal, dan pengiriman ke luar Batam via GoSend/GrabExpress dengan biaya menyesuaikan.'],
                    ['q' => 'Berapa lama estimasi waktu pengiriman?', 'a' => 'Estimasi pengiriman 3–5 hari kerja tergantung lokasi tujuan dan jasa ekspedisi yang digunakan.'],
                    ['q' => 'Bagaimana cara melacak status pesanan saya?', 'a' => 'Kunjungi halaman Lacak Pesanan, masukkan email dan kode pesanan yang kamu gunakan saat memesan.'],
                ]],
                ['kategori' => 'produk', 'label' => 'Produk & Retur', 'items' => [
                    ['q' => 'Apakah barang yang sudah dipesan bisa dikembalikan / return?', 'a' => 'Retur dapat dilakukan jika produk yang diterima tidak sesuai atau terdapat cacat produksi. Hubungi kami via WhatsApp dalam 2×24 jam setelah produk diterima dengan menyertakan foto kondisi barang.'],
                    ['q' => 'Apakah produk bisa dipesan secara custom?', 'a' => 'Ya! Kami menerima pesanan custom. Hubungi kami via WhatsApp untuk diskusi lebih lanjut mengenai desain dan estimasi harga.'],
                    ['q' => 'Apakah foto produk sesuai dengan produk aslinya?', 'a' => 'Kami berusaha menampilkan foto yang seakurat mungkin. Namun warna mungkin sedikit berbeda tergantung pengaturan layar perangkatmu.'],
                ]],
            ] as $section)
            <div class="faq-section" data-kategori="{{ $section['kategori'] }}">
                <p class="text-[11px] font-bold uppercase tracking-[0.25em] text-gray-400 mb-3">{{ $section['label'] }}</p>
                <div class="divide-y divide-gray-100">
                    @foreach($section['items'] as $item)
                    <div class="faq-item">
                        <button onclick="toggleFaq(this)"
                            class="w-full flex justify-between items-center py-4 text-left text-sm text-[#001f3f] hover:text-gray-500 transition-colors duration-150">
                            <span>{{ $item['q'] }}</span>
                            <span class="faq-icon ml-6 flex-shrink-0 text-gray-300 text-xl leading-none">+</span>
                        </button>
                        <div class="faq-answer hidden pb-4 text-sm text-gray-500 leading-relaxed pr-8">
                            {{ $item['a'] }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach

        </div>
    </div>

    {{-- CTA Box --}}
    <div class="w-full px-4 py-12 text-center flex flex-col items-center justify-center">
        <p class="text-sm text-gray-500 mb-5 mt-6">Tidak menemukan jawaban yang kamu cari? Hubungi kami langsung.</p>
        @php
            $pesanWaFaq = 'Halo admin Kriya Rajut! Saya ingin menanyakan sesuatu yang belum ada di halaman FAQ. Boleh dibantu?';
        @endphp
        <a href="https://wa.me/6285778092881?text={{ urlencode($pesanWaFaq) }}" target="_blank"
            class="inline-flex items-center gap-2 bg-[#001f3f] hover:bg-[#003366] text-white font-bold uppercase tracking-[0.2em] text-xs px-10 py-3.5 rounded-full transition-all duration-200 shadow-md hover:shadow-lg">
            <i class="fab fa-whatsapp text-sm"></i> Chat via WhatsApp
        </a>
    </div>

</div>

<script>
    function toggleFaq(btn) {
        const answer = btn.nextElementSibling;
        const icon = btn.querySelector('.faq-icon');
        const isOpen = !answer.classList.contains('hidden');
        answer.classList.toggle('hidden', isOpen);
        icon.textContent = isOpen ? '+' : '×';
        btn.classList.toggle('text-gray-400', !isOpen);
        btn.classList.toggle('text-[#001f3f]', isOpen);
    }

    function filterKategori(kat) {
        document.querySelectorAll('.faq-section').forEach(section => {
            section.style.display = (kat === 'semua' || section.dataset.kategori === kat) ? 'block' : 'none';
        });
        document.querySelectorAll('.faq-btn').forEach(btn => {
            btn.className = 'faq-btn w-full text-left px-3 py-2.5 text-xs uppercase tracking-widest transition-all duration-150 text-gray-400 hover:text-[#001f3f] border-l-2 border-transparent';
        });
        const active = document.getElementById('btn-' + kat);
        if (active) active.className = 'faq-btn w-full text-left px-3 py-2.5 text-xs uppercase tracking-widest transition-all duration-150 text-[#001f3f] font-bold border-l-2 border-[#001f3f] bg-white';
    }
</script>
@endsection
