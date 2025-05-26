<?php

use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

if (!function_exists('formatRupiah')) {
  function formatRupiah($angka)
  {
    return 'Rp. ' . number_format(
      $angka,
      0,
      ',',
      '.'
    );
  }
}

if (!function_exists('formatBulan')) {
  function formatBulan($bulan)
  {
    if (!$bulan) {
      return '-';
    }

    try {
      $date = DateTime::createFromFormat('Y-m', $bulan);
      if (!$date) {
        return '-';
      }

      // Set locale to Indonesian
      setlocale(LC_TIME, 'id_ID.utf8', 'Indonesian_indonesia.1252');

      // Format bulan dalam bahasa Indonesia
      return strftime('%B %Y', $date->getTimestamp());
    } catch (\Exception $e) {
      return '-';
    }
  }
}


if (!function_exists('formatTanggal')) {
  function formatTanggal($tanggal)
  {
    Carbon::setLocale('id');
    return Carbon::parse($tanggal)->translatedFormat('j F Y');
  }
}

if (!function_exists('formatTanggalJam')) {
  function formatTanggalJam($tanggal)
  {
    Carbon::setLocale('id');
    $carbonDate = Carbon::parse($tanggal)->setTimezone('Asia/Jakarta'); // pastikan ke WIB
    return $carbonDate->translatedFormat('j F Y, H:i') . ' WIB';
  }
}

if (!function_exists('formatNamaBulan')) {
  function formatNamaBulan($bulan)
  {
    $bulanArray = [
      '01' => 'Januari',
      '02' => 'Februari',
      '03' => 'Maret',
      '04' => 'April',
      '05' => 'Mei',
      '06' => 'Juni',
      '07' => 'Juli',
      '08' => 'Agustus',
      '09' => 'September',
      '10' => 'Oktober',
      '11' => 'November',
      '12' => 'Desember',
    ];
    $bulan = str_pad($bulan, 2, '0', STR_PAD_LEFT);
    return $bulanArray[$bulan] ?? $bulan;
  }
}
if (!function_exists('terbilang')) {
  function terbilang($angka)
  {
    $angka = abs($angka);
    $baca = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];

    if ($angka < 12) {
      return $baca[$angka];
    } elseif ($angka < 20) {
      return terbilang($angka - 10) . " belas";
    } elseif ($angka < 100) {
      return terbilang(intval($angka / 10)) . " puluh " . terbilang($angka % 10);
    } elseif ($angka < 200) {
      return "seratus " . terbilang($angka - 100);
    } elseif ($angka < 1000) {
      return terbilang(intval($angka / 100)) . " ratus " . terbilang($angka % 100);
    } elseif ($angka < 2000) {
      return "seribu " . terbilang($angka - 1000);
    } elseif ($angka < 1000000) {
      return terbilang(intval($angka / 1000)) . " ribu " . terbilang($angka % 1000);
    } elseif ($angka < 1000000000) {
      return terbilang(intval($angka / 1000000)) . " juta " . terbilang($angka % 1000000);
    } elseif ($angka < 1000000000000) {
      return terbilang(intval($angka / 1000000000)) . " milyar " . terbilang(fmod($angka, 1000000000));
    } else {
      return "Angka terlalu besar";
    }
  }
}


if (!function_exists('indoToEnglishMonth')) {
  function indoToEnglishMonth($keyword)
  {
    $map = [
      'januari' => 'january',
      'februari' => 'february',
      'maret' => 'march',
      'april' => 'april',
      'mei' => 'may',
      'juni' => 'june',
      'juli' => 'july',
      'agustus' => 'august',
      'september' => 'september',
      'oktober' => 'october',
      'november' => 'november',
      'desember' => 'december',
    ];

    $keyword = strtolower($keyword);
    $results = [];

    foreach ($map as $indo => $english) {
      if (Str::contains($indo, $keyword)) {
        $results[] = $english;
      }
    }

    return $results;
  }
}

if (!function_exists('indoToNumericMonth')) {
  function indoToNumericMonth($keyword)
  {
    $map = [
      'januari' => 1,
      'februari' => 2,
      'maret' => 3,
      'april' => 4,
      'mei' => 5,
      'juni' => 6,
      'juli' => 7,
      'agustus' => 8,
      'september' => 9,
      'oktober' => 10,
      'november' => 11,
      'desember' => 12,
    ];

    $keyword = strtolower($keyword);
    $results = [];

    foreach ($map as $indo => $numeric) {
      if (Str::contains($indo, $keyword)) {
        $results[] = $numeric;
      }
    }

    return $results;
  }
}


if (!function_exists('indoToEnglishMonthNumber')) {
  function indoToEnglishMonthNumber($keyword)
  {
    $map = [
      'januari' => 1,
      'februari' => 2,
      'maret' => 3,
      'april' => 4,
      'mei' => 5,
      'juni' => 6,
      'juli' => 7,
      'agustus' => 8,
      'september' => 9,
      'oktober' => 10,
      'november' => 11,
      'desember' => 12,
    ];

    $keyword = strtolower($keyword);
    $results = [];

    foreach ($map as $indo => $angka) {
      if (Str::contains($indo, $keyword)) {
        $results[] = $angka;
      }
    }

    return $results;
  }
}