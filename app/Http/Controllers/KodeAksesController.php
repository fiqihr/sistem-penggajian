<?php

namespace App\Http\Controllers;

use App\Models\Gaji;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Mail\KirimKodeSlipGaji;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class KodeAksesController extends Controller
{
    public function index(Request $request)
    {
        App::setLocale('id');
        $id_user = Auth::user()->id;
        $id_guru = Guru::where('id_user', $id_user)->value('id_guru');
        if ($request->ajax()) {
            return DataTables::of(Gaji::query()->where('id_guru', $id_guru)->whereNot('status', 'belum')->orderBy('id_gaji', 'desc'))
                ->addIndexColumn()
                ->editColumn('bulan', function ($row) {
                    return "Kode Slip Gaji bulan " . formatBulan($row->bulan);
                })
                ->addColumn('action', function ($row) {

                    $copyBtn = '<button class=" btn btn-primary" onclick="salinKode(`' . $row->kode_akses . '`)"><i class="fa-solid fa-clone"></i><span class="ml-1">Salin Kode</span></button>';
                    $generateBtn = '<button class="ml-2 btn btn-danger text-white" onclick="generateKode(' . $row->id_gaji . ')"><i class="fa-solid fa-rotate"></i><span class="ml-1">Generate Ulang Kode</span></span></button>';

                    return '<div class="text-center">' . $copyBtn . $generateBtn . '</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('kode_akses.index');
    }

    public function generate($id)
    {
        $gaji = Gaji::find($id);
        if (!$gaji) {
            return response()->json(['success' => false, 'message' => 'Data gaji tidak ditemukan.']);
        }

        $kodeBaru = strtoupper(Str::random(6));
        $gaji->kode_akses = $kodeBaru;
        $gaji->kode_akses_expired = now()->addMinutes(30);
        $gaji->save();

        $guru = Guru::find($gaji->id_guru);
        if (!$guru || !$guru->user || !$guru->user->email) {
            return response()->json(['success' => false, 'message' => 'Email guru tidak ditemukan.']);
        }

        try {
            Mail::to($guru->user->email)->send(new KirimKodeSlipGaji($guru, $kodeBaru));
            return response()->json(['success' => true, 'message' => 'Kode berhasil digenerate dan dikirim ulang ke email.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengirim email: ' . $e->getMessage()]);
        }
    }
}
