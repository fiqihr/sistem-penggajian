<?php

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
      return strftime('%B, %Y', $date->getTimestamp());
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
