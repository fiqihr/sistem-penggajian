<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Slip Gaji - {{ formatBulan($gaji->bulan) }} - {{ $gaji->guru->user->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 30px;
            color: #000000;
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

        .header-container {
            width: 100%;
            margin-bottom: 5px;
            border-collapse: collapse;
        }

        .logo-cell {
            width: 18%;
            vertical-align: top;
            text-align: center;
            padding-right: 10px;
        }

        .logo-cell img {
            width: 120px;
            height: auto;
        }

        .info-cell {
            width: 82%;
            vertical-align: top;
            text-align: center;
            line-height: 1.2;
        }

        .info-cell p,
        .info-cell h1,
        .info-cell h2,
        .info-cell h3 {
            margin: 1px 0;
            padding: 0;
            text-transform: uppercase;
        }

        .lp-maarif,
        .yayasan-text {
            font-size: 10.5pt;
            color: #006400;
            font-weight: bold;
        }

        .school-name {
            font-size: 22pt;
            font-weight: bold;
            color: #006400;
            margin-top: 3px;
            margin-bottom: 10px;
        }

        .program-title {
            font-size: 9.5pt;
            font-weight: bold;
            color: #B22222;
        }

        .program-list {
            font-size: 9pt;
            color: #006400;
        }

        .header-line {
            border: 0;
            border-top: 1.5px solid #000000;
            margin: 2px 0;
        }

        .header-line-green {
            border: 0;
            border-top: 1.5px solid #006400;
            margin: 2px 0;
        }

        .sub-header-info {
            font-size: 7pt;
            text-align: center;
            margin: 4px 0;
            color: #006400;
            font-weight: bold;
        }

        .address-info {
            font-size: 7pt;
            text-align: center;
            margin: 4px 0;
        }

        .document-title {
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <table class="header-container">
        <tr>
            <td class="logo-cell">
                <img src="{{ public_path('libs/img/logo-smk.png') }}" alt="Logo Sekolah">
            </td>
            <td class="info-cell">
                <p class="lp-maarif">LEMBAGA PENDIDIKAN MA'ARIF NU</p>
                <p class="yayasan-text">SEKOLAH MENENGAH KEJURUAN YAYASAN IBU HJ. CHODIJAH</p>
                <h1 class="school-name">SMK YABUJAH SEGERAN</h1>
                <h3 class="program-title">PROGRAM KEAHLIAN :</h3>
                <p class="program-list">TEKNIK OTOMOTIF, TEKNIK JARINGAN KOMPUTER & TELEKOMUNIKASI</p>
                <p class="program-list">LAYANAN KESEHATAN, TEKNOLOGI FARMASI</p>
                <div class="sub-header-info">
                    NSS/NPSN :32 2 02 18 110 03 / 20255767 <span style="margin: 0 5px;">|</span> IJIN OPERASIONAL : No.
                    421.5 /
                    Kep. 118 - Disdik / 2009
                </div>
            </td>
        </tr>
    </table>

    <hr class="header-line-green">
    <div class="address-info">
        Alamat : JL.KH. Hasyim Asy'ari No. 1/1 Segeran Kidul Kec. Juntinyuat Indramayu 45282 Telp./Fax (0234) 487664
        email: smkyabujah@yahoo.co.id
    </div>
    <hr class="header-line">
    <h2 class="document-title">SLIP GAJI</h2>
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
    <br>
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
