<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Jabatan::query())
                ->addIndexColumn()
                ->editColumn('nama_jabatan', function ($row) {
                    return $row->nama_jabatan;
                })
                ->editColumn('gaji_pokok', function ($row) {
                    return formatRupiah($row->gaji_pokok);
                })
                ->addColumn('action', function ($row) {
                    $editBtn = '<a href="' . route('jabatan.edit', $row->id_jabatan) . '" class="ml-2 btn btn-warning text-white"><i class="fa-solid fa-pen-nib"></i><span class="ml-2 ">Edit</span></a>';
                    $deleteBtn = '<form id="delete-form-' . $row->id_jabatan . '" action="' . route('jabatan.destroy', $row->id_jabatan) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="button" onclick="deleteJabatan(' . $row->id_jabatan . ')" class="btn btn-danger">
                            <i class="fa-solid fa-trash"></i><span class="ml-2 ">Hapus</span>
                        </button>
                    </form>';
                    return '<div class="text-center">' . $editBtn . $deleteBtn . '</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('jabatan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('jabatan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_jabatan' => 'required|string|max:255',
            'gaji_pokok' => 'required|numeric',
        ]);
        $simpan = Jabatan::create([
            'nama_jabatan' => $request->nama_jabatan,
            'gaji_pokok' => $request->gaji_pokok,
        ]);

        if ($simpan) {
            session()->flash('berhasil', 'Jabatan berhasil disimpan!');
            return redirect()->route('jabatan.index');
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
        $data = Jabatan::find($id);
        return view('jabatan.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_jabatan' => 'required|string|max:255',
            'gaji_pokok' => 'required|numeric',
        ]);
        $update = Jabatan::where('id_jabatan', $id)->update([
            'nama_jabatan' => $request->nama_jabatan,
            'gaji_pokok' => $request->gaji_pokok,
        ]);

        if ($update) {
            session()->flash('berhasil', 'Jabatan berhasil diubah!');
            return redirect()->route('jabatan.index');
        } else {
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $hapus = Jabatan::where('id_jabatan', $id)->delete();
        if ($hapus) {
            session()->flash('berhasil', 'Jabatan berhasil dihapus!');
            return redirect()->route('jabatan.index');
        } else {
            return redirect()->back();
        }
    }
}
