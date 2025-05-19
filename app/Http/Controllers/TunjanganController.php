<?php

namespace App\Http\Controllers;

use App\Models\Gaji;
use App\Models\Tunjangan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TunjanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Tunjangan::query()->orderBy('id_tunjangan', 'desc'))
                ->addIndexColumn()
                ->editColumn('bulan', function ($row) {
                    return formatBulan($row->bulan);
                })
                ->editColumn('id_guru', function ($row) {
                    return $row->guru->user->name;
                })
                ->editColumn('jml_tunjangan', function ($row) {
                    return formatRupiah($row->jml_tunjangan);
                })
                ->addColumn('action', function ($row) {
                    $id_guru = $row->id_guru;
                    $bulan = $row->bulan;
                    $cek_gaji = Gaji::where('id_guru', $id_guru)->where('bulan', $bulan)->first();
                    $cek_status_gaji = $cek_gaji ? $cek_gaji->status : '-';
                    if ($cek_status_gaji == 'belum') {
                        $editBtn = '<a href="' . route('tunjangan.edit', $row->id_tunjangan) . '" class="ml-2 btn btn-warning text-white"><i class="fa-solid fa-pen-nib"></i><span class="ml-2 ">Edit</span></a>';
                    } else {
                        $editBtn = '<btn onclick="peringatanBtnTunjangan()" class="ml-2 btn btn-secondary text-white" disabled><i class="fa-solid fa-pen-nib"></i><span class="ml-2 ">Edit</span></btn>';
                    }
                    // $showBtn = '<a href="' . route('jabatan.show', $row->id_jabatan) . '" class="btn btn-primary btn-user text-white"><i class="fa-solid fa-eye"></i><span class="ml-2 ">Detail</span></a>';
                    // $deleteBtn = '<form id="delete-form-' . $row->id_jabatan . '" action="' . route('jabatan.destroy', $row->id_jabatan) . '" method="POST" style="display:inline;">
                    //     ' . csrf_field() . '
                    //     ' . method_field('DELETE') . '
                    //     <button type="button" onclick="deleteJabatan(' . $row->id_jabatan . ')" class="btn btn-danger">
                    //         <i class="fa-solid fa-trash"></i><span class="ml-2 ">Hapus</span>
                    //     </button>
                    // </form>';


                    return '<div class="text-center">' . $editBtn .  '</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('tunjangan.index');
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
        $data = Tunjangan::find($id);
        return view('tunjangan.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $jml_tunjangan_lama = $request->jml_tunjangan_lama;
        $jml_tunjangan = $request->jml_tunjangan;
        $data = Tunjangan::find($id);
        $id_guru = $data->id_guru;
        $bulan = $data->bulan;

        $gaji = Gaji::where('id_guru', $id_guru)->where('bulan', $bulan)->first();
        $total_gaji = $gaji->total_gaji;

        $total_gaji_baru = ($total_gaji - $jml_tunjangan_lama) + $jml_tunjangan;

        $update_gaji = $gaji->update([
            'total_gaji' => $total_gaji_baru,
        ]);

        $update_tunjangan = $data->update([
            'jml_tunjangan' => $jml_tunjangan,
        ]);

        $update = $update_gaji && $update_tunjangan;
        if ($update) {
            session()->flash('berhasil', 'Jumlah tunjangan berhasil diupdate!');
            return redirect()->route('tunjangan.index');
        } else {
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
