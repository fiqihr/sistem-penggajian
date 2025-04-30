<?php

namespace App\Http\Controllers;

use App\Models\PotonganGaji;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PotonganGajiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(PotonganGaji::query()->orderBy('id_potongan_gaji', 'desc'))
                ->addIndexColumn()
                ->editColumn('jml_potongan', function ($row) {
                    return formatRupiah($row->jml_potongan);
                })
                ->addColumn('action', function ($row) {
                    $showBtn = '<a href="' . route('potongan-gaji.show', $row->id_potongan_gaji) . '" class="btn btn-primary btn-user text-white"><i class="fa-solid fa-eye"></i><span class="ml-2">Detail</span></a>';
                    $editBtn = '<a href="' . route('potongan-gaji.edit', $row->id_potongan_gaji) . '" class="ml-2 btn btn-warning text-white"><i class="fa-solid fa-pen-nib"></i><span class="ml-2">Edit</span></a>';
                    $deleteBtn = '<form id="delete-form-' . $row->id_potongan_gaji . '" action="' . route('potongan-gaji.destroy', $row->id_potongan_gaji) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="button" onclick="deleteClient(' . $row->id_potongan_gaji . ')" class="btn btn-danger">
                            <i class="fa-solid fa-trash"></i><span class="ml-2 ">Hapus</span>
                        </button>
                    </form>';


                    return '<div class="text-center">' . $editBtn . $deleteBtn . '</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('potongan_gaji.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('potongan_gaji.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_potongan' => 'required|string|max:255',
            'jml_potongan' => 'required|numeric',
        ]);
        $simpan = PotonganGaji::create([
            'nama_potongan' => $request->nama_potongan,
            'jml_potongan' => $request->jml_potongan,
        ]);
        if ($simpan) {
            session()->flash('berhasil', 'Potongan gaji berhasil disimpan!');
            return redirect()->route('potongan-gaji.index');
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
        $data = PotonganGaji::find($id);
        return view('potongan_gaji.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_potongan' => 'required|string|max:255',
            'jml_potongan' => 'required|numeric',
        ]);
        $data = PotonganGaji::findOrFail($id);
        $update = $data->update([
            'nama_potongan' => $request->nama_potongan,
            'jml_potongan' => $request->jml_potongan,
        ]);
        if ($update) {
            session()->flash('berhasil', 'Potongan gaji berhasil diupdate!');
            return redirect()->route('potongan-gaji.index');
        } else {
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $hapus = PotonganGaji::where('id_potongan_gaji', $id)->delete();
        if ($hapus) {
            session()->flash('berhasil', 'Potongan gaji berhasil dihapus!');
            return redirect()->route('potongan-gaji.index');
        } else {
            return redirect()->back();
        }
    }
}