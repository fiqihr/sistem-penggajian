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
        return view('dashboard', compact('cekGajiMasuk'));
    }
}
