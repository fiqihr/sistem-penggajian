<?php

namespace App\Http\Controllers;

use App\Models\Gaji;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

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
    public function show(string $id)
    {
        //
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
            return response()->json([
                'message' => 'Kode valid!',
                'id' => $gaji->id_gaji, // untuk diarahkan ke /gaji/{id}
            ]);
        } else {
            return response()->json([
                'message' => 'Kode salah atau sudah kadaluarsa.'
            ], 403);
        }
    }
}
