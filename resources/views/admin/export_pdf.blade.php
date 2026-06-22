<h2 style="text-align: center; font-family: Arial;">Laporan Pendapatan Bulanan</h2>
<table border="1" style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif;">
    <thead>
        <tr style="background-color: #ffffff;">
            <th style="padding: 8px;">ID Pesanan</th>
            <th style="padding: 8px;">Tanggal</th>
            <th style="padding: 8px;">Nomor Telepon</th>
            <th style="padding: 8px;">Nama Pembeli</th>
            <th style="padding: 8px;">Barang</th>
            <th style="padding: 8px;">Total (Murni Produk)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $p)
        <tr>
            <td style="padding: 8px;">{{ $p->id_pesanan }}</td>
            <td style="padding: 8px;">{{ $p->created_at->format('d M Y') }}</td>
            <td style="padding: 8px;">{{ $p->no_hp }}</td>
            <td style="padding: 8px;">{{ $p->nama_pembeli }}</td>
            <td style="padding: 8px;">{{ $p->nama_barang }}</td>
            <td style="padding: 8px;">Rp {{ number_format($p->total - $p->ongkir, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr style="background-color: #ffffff;">
            <td colspan="5" style="text-align: left; padding: 8px;"><strong>Total Keseluruhan</strong></td>
            <td style="padding: 8px;"><strong>Rp {{ number_format($data->sum(fn($p) => $p->total - $p->ongkir), 0, ',', '.') }}</strong></td>
        </tr>
    </tfoot>
</table>