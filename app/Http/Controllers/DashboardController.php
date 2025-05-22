<?php

namespace App\Http\Controllers;

use App\Models\Gaji;
use App\Models\Guru;
use App\Models\Jabatan;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $idUser = Auth::user()->id;
        $jumlahGuru = Guru::count();
        $jumlahJabatan = Jabatan::count();
        $idGuru = Guru::where('id_user', $idUser)->first()->id_guru ?? '';
        $cekGajiMasuk = Gaji::where('id_guru', $idGuru)->where('status', 'dikirim')->get();

        $guruTidakTetap = Guru::where('status', 'Guru Tidak Tetap')->count();
        $guruTetap = Guru::where('status', 'Guru Tetap')->count();

        $jumlahLaki = Guru::where('jenis_kelamin', 'Laki-laki')->count();
        $jumlahPerempuan = Guru::where('jenis_kelamin', 'Perempuan')->count();
        $totalGuru = $jumlahLaki + $jumlahPerempuan;

        $bulanSekarang = date('Y-m');

        // 1. Ambil SEMUA ID guru sebagai Collection (daftar ID)
        $daftarIdSemuaGuru = Guru::pluck('id_guru'); // Ini adalah Collection, bukan integer

        // 2. Hitung jumlah total guru (jika Anda masih memerlukannya untuk dd atau logika lain)
        $jumlahTotalGuru = $daftarIdSemuaGuru->count();

        // 3. Hitung jumlah guru yang sudah melakukan presensi di bulan ini
        $jumlahGuruSudahPresensi = Presensi::where('bulan', $bulanSekarang)
            ->whereIn('id_guru', $daftarIdSemuaGuru) // Gunakan Collection ID di sini
            ->pluck('id_guru') // Ambil id_guru dari hasil presensi
            ->unique()         // Pastikan setiap guru dihitung sekali saja
            ->count();         // Hitung jumlah guru yang unik

        $persentasePresensi = 0; // Default jika tidak ada guru
        if ($jumlahTotalGuru > 0) {
            $persentasePresensi = ($jumlahGuruSudahPresensi / $jumlahTotalGuru) * 100;
        }

        // dd($jumlahTotalGuru, $jumlahGuruSudahPresensi);
        // $semuaPresensi = Presensi::where('bulan', $bulanSekarang)->whereIn('id_guru', $semuaIdGuru)->get();

        // hitung persen untuk progress bar
        $persenLaki = $totalGuru > 0 ? round(($jumlahLaki / $totalGuru) * 100, 1) : 0;
        $persenPerempuan = $totalGuru > 0 ? round(($jumlahPerempuan / $totalGuru) * 100, 1) : 0;

        return view('dashboard', compact(
            'cekGajiMasuk',
            'guruTidakTetap',
            'guruTetap',
            'jumlahGuru',
            'jumlahJabatan',
            'jumlahLaki',
            'jumlahPerempuan',
            'persenLaki',
            'persenPerempuan',
            'bulanSekarang',
            'jumlahTotalGuru',
            'jumlahGuruSudahPresensi',
            'persentasePresensi'
        ));
    }
}
