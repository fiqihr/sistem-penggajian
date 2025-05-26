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
            return DataTables::of(Tunjangan::query()->orderBy('id_tunjangan', 'asc'))
                ->addIndexColumn()
                ->editColumn('bulan', function ($row) {
                    return formatBulan($row->bulan);
                })
                ->editColumn('jml_tunjangan', function ($row) {
                    return formatRupiah($row->jml_tunjangan);
                })
                ->addColumn('action', function ($row) {
                    $editBtn = '<a href="' . route('tunjangan.edit', $row->id_tunjangan) . '" class="ml-2 btn btn-warning text-white"><i class="fa-solid fa-pen-nib"></i><span class="ml-2 ">Edit</span></a>';
                    $deleteBtn = '<form id="delete-form-' . $row->id_tunjangan . '" action="' . route('tunjangan.destroy', $row->id_tunjangan) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="button" onclick="deleteTunjangan(' . $row->id_tunjangan . ')" class="btn btn-danger">
                            <i class="fa-solid fa-trash"></i><span class="ml-2 ">Hapus</span>
                        </button>
                    </form>';
                    return '<div class="text-center">' . $editBtn . $deleteBtn . '</div>';
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
        return view('tunjangan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jml_tunjangan' => 'required',
            'nama_tunjangan' => 'required',
        ]);

        $simpan = Tunjangan::create([
            'jml_tunjangan' => $request->jml_tunjangan,
            'nama_tunjangan' => $request->nama_tunjangan,
        ]);

        if ($simpan) {
            session()->flash('berhasil', 'Tunjangan berhasil disimpan!');
            return redirect()->route('tunjangan.index');
        } else {
            return redirect()->back();
        }
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
        $jml_tunjangan = $request->jml_tunjangan;
        $nama_tunjangan = $request->nama_tunjangan;

        $update = Tunjangan::where('id_tunjangan', $id)->update([
            'jml_tunjangan' => $jml_tunjangan,
            'nama_tunjangan' => $nama_tunjangan,
        ]);

        if ($update) {
            session()->flash('berhasil', 'Tunjangan berhasil diupdate!');
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
        $hapus = Tunjangan::where('id_tunjangan', $id)->delete();
        if ($hapus) {
            session()->flash('berhasil', 'Tunjangan berhasil dihapus!');
            return redirect()->route('tunjangan.index');
        } else {
            return redirect()->back();
        }
    }
}
