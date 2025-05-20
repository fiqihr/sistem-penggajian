<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Slip Gaji</title>
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

        <style>.table {
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
    </style>
</head>

<body>
    <table class="table w-100">
        <tr>
            <td class="w-50">
                <h2>SLIP GAJI SMK YABUJJAH</h2>
            </td>
            <td class="w-50">
                <img src="{{ public_path('libs/img/logo-smk.png') }}" alt="" style="width: 25%; float: right;">
            </td>

        </tr>
    </table>
    <hr style="margin-bottom: 20px; margin-top:20px;">
    <table class="table w-50">
        <tr>
            <td>Periode</td>
            <td>:</td>
            <td>{{ formatBulan($gaji->bulan) }}</td>
        </tr>
        <tr>
            <td>Nama Guru</td>
            <td>:</td>
            <td>{{ $gaji->guru->user->name }}</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>:</td>
            <td>{{ $gaji->guru->jabatan->nama_jabatan }}</td>
        </tr>
        <tr>
            <td>Status</td>
            <td>:</td>
            <td>{{ $gaji->guru->status }}</td>
        </tr>
    </table>
    <div class="line"></div>
    <div class="section-title">PENERIMAAN</div>
    <table class="salary-table">
        <tr>
            <td class="label">- Gaji Pokok</td>
            <td class="value">{{ formatRupiah($gaji->guru->jabatan->gaji_pokok) }}</td>
        </tr>
        <tr>
            <td class="label">- Tunjangan ({{ $nama_tunjangan }})</td>
            <td class="value">{{ formatRupiah($jml_tunjangan) }}</td>
        </tr>
        <tr>
            <td class="label"><strong>Total Penghasilan Bruto</strong></td>
            <td class="value"><strong>{{ formatRupiah($total_bruto) }}</strong></td>
        </tr>
    </table>
    <div class="line"></div>
    <div class="section-title">POTONGAN</div>
    <table class="salary-table">
        <tr>
            <td>
                - Ketidakhadiran (Tidak Hadir: {{ $tidak_hadir }} x
                {{ formatRupiah($potongan_tidak_hadir) }},
                Sakit:
                {{ $sakit }} x {{ formatRupiah($potongan_sakit) }})
            </td>
            <td class="value">{{ formatRupiah($potongan_sakit_dan_tidak_hadir) }}</td>
        </tr>
        @foreach ($semua_jenis_potongan as $potongan)
            <tr>
                <td>
                    - {{ $potongan->nama_potongan }}
                </td>
                <td class="value">{{ formatRupiah($potongan->jml_potongan) }}</td>
            </tr>
        @endforeach
        {{-- <tr>
            <td>
                - BPR
            </td>
            <td class="value">{{ formatRupiah($potongan_bpr) }}</td>
        </tr>
        <tr>
            <td>
                - Lazisnu <br>
            </td>
            <td class="value">{{ formatRupiah($potongan_lazisnu) }}</td>
        </tr> --}}

        <tr class="bold">
            <td class="label">Total Potongan</td>
            <td class="value">{{ formatRupiah($total_potongan) }}</td>
        </tr>
    </table>
    <div class="line"></div>
    <table class="salary-table">
        <tr class="bold">
            <td class="label">PENERIMA BERSIH</td>
            <td class="value">{{ formatRupiah($gaji->total_gaji) }}</td>
        </tr>
    </table>
    <div class="terbilang">
        <strong>Terbilang:</strong><span style="font-style: italic"> {{ ucwords(terbilang($gaji->total_gaji)) }}
            Rupiah</span>
    </div>

    <br><br>

    <table class="signature">
        <tr>
            <td class="w-50">Guru</td>
            <td class="w-50">{{ formatTanggal($gaji->created_at) }},<br>SMK Yabujjah</td>
        </tr>
        <tr>
            <td style="height: 80px;">{{ $gaji->guru->user->name }}</td>
            <td>Bendahara</td>
        </tr>
    </table>

</body>

</html>
