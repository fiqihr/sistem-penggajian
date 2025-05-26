<?php

namespace App\Http\Controllers;

use App\Models\Gaji;
use App\Models\Guru;
use App\Models\Jabatan;
use App\Models\Presensi;
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
        $daftarIdSemuaGuru = Guru::pluck('id_guru');
        $jumlahTotalGuru = $daftarIdSemuaGuru->count();

        $jumlahGuruSudahPresensi = Presensi::where('bulan', $bulanSekarang)
            ->whereIn('id_guru', $daftarIdSemuaGuru)
            ->pluck('id_guru')
            ->unique()
            ->count();

        $persentasePresensi = 0;
        if ($jumlahTotalGuru > 0) {
            $persentasePresensi = ($jumlahGuruSudahPresensi / $jumlahTotalGuru) * 100;
        }

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
