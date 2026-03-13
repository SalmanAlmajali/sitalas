<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Print Report Proposal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
        }

        h2 {
            margin-bottom: 8px;
        }

        .filter-info {
            margin-bottom: 16px;
        }

        .filter-info div {
            margin-bottom: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th, td {
            padding: 8px;
            vertical-align: top;
            text-align: left;
        }

        @media print {
            @page {
                size: A4 landscape;
                margin: 12mm;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <h2>Report Proposal</h2>

    <div class="filter-info">
        <div><strong>Dari Tanggal:</strong> {{ $dateFrom ?: '-' }}</div>
        <div><strong>Sampai Tanggal:</strong> {{ $dateTo ?: '-' }}</div>
        <div><strong>Direktorat:</strong> {{ $direktorat ?: '-' }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>No Reg</th>
                <th>Direktorat</th>
                <th>Pengirim</th>
                <th>Perihal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($proposals as $proposal)
                <tr>
                    <td>{{ $proposal->tanggal ? \Carbon\Carbon::parse($proposal->tanggal)->format('d M Y') : '-' }}</td>
                    <td>{{ $proposal->no_reg }}</td>
                    <td>{{ $proposal->unitPengolah->direktorat ?? '-' }}</td>
                    <td>{{ $proposal->pengirim }}</td>
                    <td>{{ $proposal->perihal }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>