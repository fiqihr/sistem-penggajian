<?php

namespace App\Http\Controllers;

use App\Models\Gaji;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $idUser = Auth::user()->id;
        $idGuru = Guru::where('id_user', $idUser)->first()->id_guru ?? '';
        $cekGajiMasuk = Gaji::where('id_guru', $idGuru)->where('status', 'dikirim')->get();

        $guruTidakTetap = Guru::where('status', 'Guru Tidak Tetap')->count();
        $guruTetap = Guru::where('status', 'Guru Tetap')->count();

        $jumlahLaki = Guru::where('jenis_kelamin', 'Laki-laki')->count();
        $jumlahPerempuan = Guru::where('jenis_kelamin', 'Perempuan')->count();
        $totalGuru = $jumlahLaki + $jumlahPerempuan;

        // hitung persen untuk progress bar
        $persenLaki = $totalGuru > 0 ? round(($jumlahLaki / $totalGuru) * 100, 1) : 0;
        $persenPerempuan = $totalGuru > 0 ? round(($jumlahPerempuan / $totalGuru) * 100, 1) : 0;

        return view('dashboard', compact(
            'cekGajiMasuk',
            'guruTidakTetap',
            'guruTetap',
            'jumlahLaki',
            'jumlahPerempuan',
            'persenLaki',
            'persenPerempuan'
        ));
    }
}
