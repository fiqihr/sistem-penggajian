<?php

namespace App\Http\Controllers;

use App\Models\Gaji;
use App\Models\Guru;
use App\Models\PotonganGaji;
use App\Models\Presensi;
use App\Models\Tunjangan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class GajiSayaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        App::setLocale('id');
        $id_user = Auth::user()->id;
        $id_guru = Guru::where('id_user', $id_user)->value('id_guru');
        if ($request->ajax()) {
            return DataTables::of(Gaji::query()->where('id_guru', $id_guru)->whereNot('status', 'belum')->orderBy('id_gaji', 'desc'))
                ->addIndexColumn()
                ->editColumn('bulan', function ($row) {
                    return formatBulan($row->bulan);
                })
                // ->editColumn('id_guru', function ($row) {
                //     return $row->guru->user->name;
                // })
                // ->editColumn('jabatan', function ($row) {
                //     return $row->guru->jabatan->nama_jabatan;
                // })
                // ->editColumn('gaji_pokok', function ($row) {
                //     return formatRupiah($row->guru->jabatan->gaji_pokok);
                // })
                // ->editColumn('tj_transport', function ($row) {
                //     return formatRupiah($row->guru->jabatan->tj_transport);
                // })
                // ->editColumn('uang_makan', function ($row) {
                //     return formatRupiah($row->guru->jabatan->uang_makan);
                // })
                // ->editColumn('potongan', function ($row) {
                //     return formatRupiah($row->potongan);
                // })
                ->editColumn('total_gaji', function ($row) {
                    return formatRupiah($row->total_gaji);
                })
                ->addColumn('action', function ($row) {
                    // $showBtn = '<a href="' . route('potongan-gaji.show', $row->id_potongan_gaji) . '" class="btn btn-primary btn-user text-white"><i class="fa-solid fa-eye"></i><span class="ml-2">Detail</span></a>';
                    $editBtn = '<a href="#" class="ml-2 btn btn-warning text-white"><i class="fa-solid fa-pen-nib"></i><span class="ml-2">Edit</span></a>';
                    // $editBtn = '<a href="' . route('potongan-gaji.edit', $row->id_potongan_gaji) . '" class="ml-2 btn btn-warning text-white"><i class="fa-solid fa-pen-nib"></i><span class="ml-2">Edit</span></a>';
                    // $deleteBtn = '<form id="delete-form-' . $row->id_potongan_gaji . '" action="' . route('potongan-gaji.destroy', $row->id_potongan_gaji) . '" method="POST" style="display:inline;">
                    //     ' . csrf_field() . '
                    //     ' . method_field('DELETE') . '
                    //     <button type="button" onclick="deleteClient(' . $row->id_potongan_gaji . ')" class="btn btn-danger">
                    //         <i class="fa-solid fa-trash"></i><span class="ml-2 ">Hapus</span>
                    //     </button>
                    // </form>';
                    if ($row->status == 'belum') {
                        $cetakBtn = '<a disabled class="ml-2 btn btn-secondary text-white"><i class="fa-solid fa-print"></i><span class="ml-2">Cetak</span></a>';
                    } else if ($row->status == 'dikirim') {
                        // $cetakBtn = '<a href="' . route('gaji.show', $row->id_gaji) . '" onclick="window.open(this.href, \'_blank\'); location.reload(); return false;"  class="ml-2 btn btn-warning text-white"><i class="fa-solid fa-print"></i><span class="ml-2">Cetak</span></a>';
                        $cetakBtn = '<btn onclick="cekKode(' . $row->id_gaji . ',\'' . $row->guru->user->email . '\')"  class="ml-2 btn btn-warning text-white"><i class="fa-solid fa-print"></i><span class="ml-2">Cetak</span></btn>';
                    } else {
                        $cetakBtn = '<btn onclick="cekKode(' . $row->id_gaji . ',\'' . $row->guru->user->email . '\')" class="ml-2 btn btn-success text-white"><i class="fa-solid fa-file-circle-check"></i><span class="ml-2">Dilihat</span></btn>';
                    }


                    return '<div class="text-center">' . $cetakBtn . '</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('gaji_saya.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $encryptedId)
    {
        try {
            $id = Crypt::decryptString($encryptedId);
        } catch (DecryptException $e) {
            abort(404, 'Data gaji tidak ditemukan atau tautan tidak valid.');
        }

        $gaji = Gaji::with('guru', 'jabatan')->where('id_gaji', $id)->first();

        $bulan_presensi = $gaji->bulan;
        $id_guru = $gaji->id_guru;
        $id_tunjangan = $gaji->id_tunjangan;
        $presensi = Presensi::where('bulan', $bulan_presensi)->where('id_guru', $id_guru)->first();

        $gaji_pokok = Guru::where('id_guru', $id_guru)->first()->jabatan->gaji_pokok;

        if (!$id_tunjangan == null) {
            $jml_tunjangan = Tunjangan::where('id_tunjangan', $id_tunjangan)->first()->jml_tunjangan;
            $nama_tunjangan = Tunjangan::where('id_tunjangan', $id_tunjangan)->first()->nama_tunjangan;
        } else {
            $jml_tunjangan = 0;
            $nama_tunjangan = "-";
        }

        $total_bruto = $gaji_pokok + $jml_tunjangan;


        $sakit = $presensi->sakit ?? 0;
        $tidak_hadir = $presensi->tidak_hadir ?? 0;

        $potongan_sakit = PotonganGaji::where('nama_potongan', 'Sakit')->first()->jml_potongan;
        $potongan_tidak_hadir = PotonganGaji::where('nama_potongan', 'Tidak Hadir')->first()->jml_potongan;
        // $potongan_bpr = PotonganGaji::where('nama_potongan', 'BPR')->first()->jml_potongan;
        // $potongan_lazisnu = PotonganGaji::where('nama_potongan', 'Lazisnu')->first()->jml_potongan;

        $semua_jenis_potongan = PotonganGaji::where('nama_potongan', 'not like', '%sakit%')->where('nama_potongan', 'not like', '%tidak hadir%')->get();
        $cek_jml_potongan = [];
        foreach ($semua_jenis_potongan as $potongan) {
            $cek_jml_potongan[] = $potongan->jml_potongan;
        }
        $foreach_potongan_lain = array_sum($cek_jml_potongan);

        $total_potongan_tidak_hadir = $potongan_tidak_hadir * $tidak_hadir;
        $total_potongan_sakit = $potongan_sakit * $sakit;

        $potongan_sakit_dan_tidak_hadir = $total_potongan_tidak_hadir + $total_potongan_sakit;
        $total_potongan = $potongan_sakit_dan_tidak_hadir + $foreach_potongan_lain;


        if (Auth::user()->hak_akses == 'guru') {
            Gaji::where('id_gaji', $id)->update(['status' => 'diterima']);
        }

        // dd($alpha, $sakit, $jmlPotonganAlpha, $jmlPotonganSakit);
        $bulan_gaji = $gaji->bulan;
        $nama_guru = $gaji->guru->user->name;

        $pdf = Pdf::loadView('gaji.slip', [
            'gaji' => $gaji,
            'tidak_hadir' => $tidak_hadir,
            'sakit' => $sakit,
            'potongan_tidak_hadir' => $potongan_tidak_hadir,
            'potongan_sakit' => $potongan_sakit,
            // 'potongan_bpr' => $potongan_bpr,
            // 'potongan_lazisnu' => $potongan_lazisnu,
            'tidak_hadir' => $tidak_hadir,
            'sakit' => $sakit,
            'jml_tunjangan' => $jml_tunjangan,
            'nama_tunjangan' => $nama_tunjangan,
            'total_bruto' => $total_bruto,
            'potongan_sakit_dan_tidak_hadir' => $potongan_sakit_dan_tidak_hadir,
            'total_potongan' => $total_potongan,
            'semua_jenis_potongan' => $semua_jenis_potongan,
        ])->setPaper('A4', 'portrait');

        $file_name = 'Slip Gaji - ' . formatBulan($bulan_gaji) . ' - ' . $nama_guru . '.pdf';
        return $pdf->stream($file_name);
        // return view('gaji.slip', compact('gaji', 'alpha', 'sakit', 'jmlPotonganAlpha', 'jmlPotonganSakit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function cekKode(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'kode' => 'required|string',
        ]);

        $gaji = Gaji::find($request->id);

        if (!$gaji) {
            return response()->json(['message' => 'Data gaji tidak ditemukan.'], 404);
        }

        // Cek apakah status dikirim dan kode sesuai dan belum kadaluarsa
        if (
            $gaji->status !== 'belum' &&
            $gaji->kode_akses === $request->kode &&
            now()->lt($gaji->kode_akses_expired)
        ) {
            $encryptedIdGaji = Crypt::encryptString($gaji->id_gaji);
            return response()->json([
                'success' => true,
                'message' => 'Kode valid!',
                'encrypted_id' => $encryptedIdGaji,
            ]);
        } else {
            return response()->json([
                'message' => 'Kode salah atau sudah kadaluarsa.'
            ], 403);
        }
    }
}
