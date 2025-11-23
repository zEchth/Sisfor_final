<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        th {
            background: #eee;
        }
    </style>
</head>
<body>
    <h3>Laporan Keuangan</h3>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Item</th>
                <th>Kategori</th>
                <th>Harga Satuan</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Tipe</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($data as $t)
                <tr>
                    <td>{{ $t->transaction_date->format('d-m-Y') }}</td>
                    <td>{{ $t->item->name ?? '-' }}</td>
                    <td>{{ $t->category->name ?? '-' }}</td>
                    <td>Rp {{ number_format($t->amount) }}</td>
                    <td>{{ $t->quantity }}</td>
                    <td>Rp {{ number_format($t->total_amount) }}</td>
                    <td>{{ $t->transaction_type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
