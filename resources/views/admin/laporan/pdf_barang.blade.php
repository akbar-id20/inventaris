<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Barang</title>
    <style>
        @page { margin: 40px 50px; }
        body { font-family: 'Times', serif; font-size: 12pt; color: #000; }

        .kop { text-align: center; border-bottom: 4px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .kop h2 { margin: 0; font-size: 18pt; letter-spacing: 1px; }
        .kop p { margin: 3px 0 0 0; font-size: 11pt; }

        .judul { text-align: center; margin-bottom: 18px; }
        .judul h3 { margin: 0; font-size: 14pt; text-decoration: underline; }
        .judul p { margin: 4px 0 0 0; font-size: 11pt; }

        table.data { width: 100%; border-collapse: collapse; }
        table.data th, table.data td { border: 1px solid #000; padding: 7px 9px; }
        table.data th { background-color: #d9d9d9; text-align: center; }
        table.data td.c { text-align: center; }
        table.data tr.total td { font-weight: bold; background-color: #f2f2f2; }

        .ttd { width: 100%; margin-top: 40px; }
        .ttd td { width: 50%; text-align: center; vertical-align: top; }
        .ttd .spasi { height: 70px; }
    </style>
</head>
<body>

    <div class="kop">
        <h2>SISTEM INFORMASI INVENTARIS</h2>
        <p>Laporan Pengelolaan Data Barang</p>
    </div>

    <div class="judul">
        <h3>LAPORAN DATA BARANG</h3>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y, H:i') }} WIB</p>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th width="8%">No</th>
                <th width="22%">Kode Barang</th>
                <th>Nama Barang</th>
                <th width="15%">Stok</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $key => $item)
                <tr>
                    <td class="c">{{ $key + 1 }}</td>
                    <td class="c">{{ $item->kode }}</td>
                    <td>{{ $item->nama }}</td>
                    <td class="c">{{ $item->stok }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="c">Belum ada data barang</td>
                </tr>
            @endforelse
            <tr class="total">
                <td colspan="3" style="text-align:right;">Total Stok</td>
                <td class="c">{{ $data->sum('stok') }}</td>
            </tr>
        </tbody>
    </table>

    <table class="ttd">
        <tr>
            <td></td>
            <td>
                Banjarmasin, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}<br>
                Admin
                <div class="spasi"></div>
                <u>{{ auth()->user()->username ?? 'Admin' }}</u>
            </td>
        </tr>
    </table>

</body>
</html>