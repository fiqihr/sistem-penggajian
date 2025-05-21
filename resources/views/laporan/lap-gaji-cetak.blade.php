<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Laporan Gaji SMK Yabujjah - Periode {{ formatBulan($bulan) }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            margin: 40px;
        }

        h2 {
            text-align: left;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .header,
        .data-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .data-table td {
            padding: 5px;
            vertical-align: top;
        }

        .line {
            border-top: 1px solid #000;
            margin: 15px 0;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .salary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .salary-table td {
            padding: 4px;
        }

        .salary-table .label {
            width: 80%;
        }

        .salary-table .value {
            text-align: right;
            width: 20%;
        }

        .bold {
            font-weight: bold;
        }

        .signature {
            margin-top: 50px;
            width: 100%;
            text-align: center;
        }

        .signature td {
            padding: 10px;
        }

        .terbilang {
            margin-top: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-bordered,
        .table-bordered td,
        .table-bordered th {
            border: 1px solid #dee2e6;
        }

        .bg-primary {
            background-color: #0d6efd !important;
            color: white;
        }

        .w-100 {
            width: 100% !important;
        }

        .w-50 {
            width: 50% !important;
        }

        .row::after {
            content: "";
            display: table;
            clear: both;
        }

        .col-6 {
            float: left;
            width: 50%;
            box-sizing: border-box;
            padding: 5px;
        }

        #data-gaji {
            border-collapse: collapse;
            width: 100%;
        }

        #data-gaji th,
        #data-gaji td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        #data-gaji th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        #data-gaji tr:hover {
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>
    <table class="table w-100">
        <tr>
            <td class="" style="width:75%;">
                <h2>LAPORAN GAJI GURU SMK YABUJJAH</h2>
            </td>
            <td class="" style="width:25%;">
                <img src="{{ public_path('libs/img/logo-smk.png') }}" alt="" style="width: 50%; float: right;">
            </td>
        </tr>
    </table>
    <hr style="margin-bottom: 20px; margin-top:20px;">
    <table class="table w-50" style="margin-bottom:20px;">
        <tr>
            <td>Periode</td>
            <td>:</td>
            <td>{{ formatBulan($bulan) }}</td>
        </tr>
    </table>
    <table id="data-gaji" class="w-100">
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Guru</th>
                <th>Jabatan</th>
                <th>Jumlah Gaji</th>
                <th>Tunjangan</th>
                <th>Potongan</th>
                <th>Total Gaji</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($semuaGaji as $item)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td>{{ $item->guru->user->name }}</td>
                    <td>{{ $item->guru->jabatan->nama_jabatan }}</td>
                    <td>{{ formatRupiah($item->guru->jabatan->gaji_pokok) }}
                    <td>{{ formatRupiah($item->tunjangan->jml_tunjangan) }} <br>
                        ({{ $item->tunjangan->nama_tunjangan }})
                    </td>
                    <td>{{ formatRupiah($item->potongan) }}</td>
                    <td><strong>{{ formatRupiah($item->total_gaji) }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <table class="signature" style="width: 100%;">
        <tr>
            <td style="width: 50%;"></td>
            <td style="width: 50%; text-align: right;">
                {{ formatTanggal(now()) }},<br>
                SMK Yabujjah
            </td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align: right;">Bendahara</td>
        </tr>
    </table>

</body>

</html>
