<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Data Withdraw</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        h1 {
            color: #4a4a4a;
            border-bottom: 2px solid #f4a460;
            padding-bottom: 10px;
        }
        .info-section {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
            font-size: 14px;
        }
        .table th {
            background-color: #f4a460;
            color: white;
        }
        .table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Export Data Withdraw</h1>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Jumlah</th>
                <th>Bank</th>
                <th>Nama Penerima</th>
                <th>Nomor Rekening</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($withdraws as $withdraw)
                <tr>
                    <td>{{ $withdraw->user->name ?? '-' }}</td>
                    <td>{{ 'Rp ' . number_format($withdraw->amount, 0, ',', '.') }}</td>
                    <td>{{ $withdraw->bank_account ?? '-' }}</td>
                    <td>{{ $withdraw->in_the_name_of ?? '-' }}</td>
                    <td>{{ $withdraw->no_bank_account ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
