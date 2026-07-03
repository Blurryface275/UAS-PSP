<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan ({{ $startDate }} - {{ $endDate }})</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
        }
        .header p {
            margin: 5px 0;
            font-size: 12px;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        table td.num {
            text-align: right;
        }
        table td.center {
            text-align: center;
        }
        .total-row th {
            background-color: #f2f2f2;
            text-align: right;
            font-size: 14px;
        }
        .total-row th.val {
            text-align: right;
            color: #d9534f;
        }
        .footer {
            margin-top: 50px;
            width: 100%;
        }
        .footer td {
            border: none;
            text-align: center;
            padding: 20px;
        }
        .signature-line {
            margin-top: 50px;
            border-top: 1px solid #333;
            display: inline-block;
            width: 200px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Rekapitulasi Penjualan</h1>
        <p>Aplikasi Penjualan dan Pembelian - UAS</p>
        <p>Periode Laporan: <strong>{{ \Carbon\Carbon::parse($startDate)->format('d F Y') }}</strong> s/d <strong>{{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</strong></p>
    </div>

    @if($sales->count() > 0)
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal (WIB)</th>
                <th>No Invoice</th>
                <th>Customer</th>
                <th>Produk Dibeli & Qty</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $index => $sale)
            <tr>
                <td class="center">{{ $index + 1 }}</td>
                <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                <td class="center">{{ $sale->invoice_number }}</td>
                <td>{{ $sale->user->name ?? 'Guest' }}</td>
                <td>
                    <ul style="margin: 0; padding-left: 15px;">
                        @foreach($sale->details as $detail)
                            <li>{{ $detail->product->name ?? 'No Name' }} (x{{ $detail->qty }})</li>
                        @endforeach
                    </ul>
                </td>
                <td class="num">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <th colspan="5">TOTAL KEUNTUNGAN KOTOR PADA PERIODE INI :</th>
                <th class="val">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
    @else
    <p style="text-align: center; margin-top: 50px; font-style: italic;">Tidak ada riwayat transaksi penjualan terkirim dalam periode tanggal yang dipilih pengguna.</p>
    @endif

    <table class="footer">
        <tr>
            <td style="width: 50%;"></td>
            <td style="width: 50%;">
                <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y, H:i') }}</p>
                <p>Mengetahui,</p>
                <br><br><br>
                <span class="signature-line"></span>
                <p><strong>Admin / Manajer</strong></p>
            </td>
        </tr>
    </table>

</body>
</html>
