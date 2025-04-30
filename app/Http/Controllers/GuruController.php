<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Jabatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Guru::with('user', 'jabatan')->orderBy('id_guru', 'desc'))
                ->addIndexColumn()
                ->editColumn('name', function ($row) {
                    return $row->user ? $row->user->name : '-';
                })
                ->editColumn('nama_jabatan', function ($row) {
                    return $row->jabatan ? $row->jabatan->nama_jabatan : '-';
                })
                ->editColumn('tanggal_masuk', function ($row) {
                    return formatTanggal($row->tanggal_masuk);
                })
                ->addColumn('action', function ($row) {
                    $showBtn = '<a href="' . route('guru.show', $row->id_guru) . '" class="btn btn-primary text-white"><i class="fa-solid fa-eye"></i><span class="ml-2">Detail</span></a>';
                    $editBtn = '<a href="' . route('guru.edit', $row->id_guru) . '" class=" ml-1 btn btn-warning text-white"><i class="fa-solid fa-pen-nib"></i><span class="ml-2 ">Edit</span></a>';
                    $deleteBtn = '<form id="delete-form-' . $row->id_guru . '" action="' . route('guru.destroy', $row->id_guru) . '" method="POST" style="display:inline;">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="button" onclick="deleteGuru(' . $row->id_guru . ')" class="btn btn-danger">
                                <i class="fa-solid fa-trash"></i><span class="ml-2 ">Hapus</span>
                            </button>
                        </form>';


                    return '<div class="text-center">' . $showBtn . $editBtn . $deleteBtn . '</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('guru.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jabatan = Jabatan::all();
        return view('guru.create', compact('jabatan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required',
            'nig' => 'required',
            'jenis_kelamin' => 'required',
            'id_jabatan' => 'required',
            'status' => 'required',
            'tanggal_masuk' => 'required|date',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);
        $simpanUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        $idUser = $simpanUser->id;
        $simpanGuru = Guru::create([
            'id_user' => $idUser,
            'nig' => $request->nig,
            'jenis_kelamin' => $request->jenis_kelamin,
            'id_jabatan' => $request->id_jabatan,
            'status' => $request->status,
            'tanggal_masuk' => $request->tanggal_masuk,
            'photo' => 'default.svg'
        ]);
        if ($simpanGuru) {
            session()->flash('berhasil', 'Data Guru berhasil disimpan!');
            return redirect()->route('guru.index');
        } else {
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $guru = Guru::with('user', 'jabatan')->find($id);
        if (!$guru) {
            return redirect()->back();
        }
        return view('guru.show', compact('guru'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $jabatan = Jabatan::all();
        $guru = Guru::with('user', 'jabatan')->find($id);
        if (!$guru) {
            return redirect()->back();
        }
        return view('guru.edit', compact('guru', 'jabatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'nig' => 'required',
            'jenis_kelamin' => 'required',
            'id_jabatan' => 'required',
            'status' => 'required',
            'tanggal_masuk' => 'required|date',
        ]);
        $guru = Guru::find($id);
        if (!$guru) {
            return redirect()->back();
        }
        $guru->user->update([
            'name' => $request->name,
        ]);
        $update = $guru->update([
            'nig' => $request->nig,
            'jenis_kelamin' => $request->jenis_kelamin,
            'id_jabatan' => $request->id_jabatan,
            'status' => $request->status,
            'tanggal_masuk' => $request->tanggal_masuk,
        ]);
        if ($update) {
            session()->flash('berhasil', 'Data Guru berhasil diubah!');
            return redirect()->route('guru.index');
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

    public function uploadPhoto(Request $request, $id)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $guru = Guru::findOrFail($id);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');

            // Generate nama file hash
            $hashedName = Str::random(10) . '.' . $file->getClientOriginalExtension();

            // Simpan ke disk 'public' -> storage/app/public/images/
            Storage::disk('public')->putFileAs('images', $file, $hashedName);

            // Hapus foto lama jika ada
            if ($guru->photo && Storage::disk('public')->exists('images/' . $guru->photo)) {
                Storage::disk('public')->delete('images/' . $guru->photo);
            }

            // Simpan nama file ke database
            $guru->photo = $hashedName;
            $guru->save();

            return response()->json([
                'message' => 'Foto berhasil diunggah.',
                'photo' => $hashedName
            ]);
        }

        return response()->json(['error' => 'Gagal mengunggah foto.'], 400);
    }
}
