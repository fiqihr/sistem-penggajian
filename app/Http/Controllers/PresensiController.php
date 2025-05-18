<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB; // Tambahkan ini kalau belum ada
use Barryvdh\DomPDF\Facade\Pdf;

class PresensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Presensi::with('guru')->orderBy('id_presensi', 'desc');

            if ($request->filled('bulan')) {
                $query->whereRaw('SUBSTRING(TRIM(bulan), 6, 2) = ?', [$request->bulan]);
            }

            if ($request->filled('tahun')) {
                $query->whereRaw('SUBSTRING(TRIM(bulan), 1, 4) = ?', [$request->tahun]);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('bulan', function ($row) {
                    return formatBulan($row->bulan);
                })
                ->editColumn('nama_guru', function ($row) {
                    return $row->guru ? $row->guru->user->name : '-';
                })
                ->addColumn('action', function ($row) {
                    $editBtn = '<a href="' . route('presensi.edit', $row->id_presensi) . '" class="btn btn-warning text-white ml-2"><i class="fa-solid fa-pen-nib"></i><span class="ml-2">Edit</span></a>';
                    $deleteBtn = '<form id="delete-form-' . $row->id_presensi . '" action="' . route('presensi.destroy', $row->id_presensi) . '" method="POST" style="display:inline;">
                    ' . csrf_field() . '
                    ' . method_field('DELETE') . '
                    <button type="button" onclick="deleteJabatan(' . $row->id_presensi . ')" class="btn btn-danger">
                        <i class="fa-solid fa-trash"></i><span class="ml-2">Hapus</span>
                    </button>
                </form>';

                    return '<div class="text-center">' . $editBtn . $deleteBtn . '</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        // Ambil daftar bulan dan tahun unik dari tabel presensi
        $list_bulan = Presensi::selectRaw('DISTINCT(SUBSTRING(bulan, 6, 2)) as bulan')
            ->orderBy('bulan')
            ->pluck('bulan');

        $list_tahun = Presensi::selectRaw('DISTINCT(SUBSTRING(bulan, 1, 4)) as tahun')
            ->orderBy('tahun')
            ->pluck('tahun');

        return view('presensi.index', compact('list_bulan', 'list_tahun'));
    }

    // public function index(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $query = Presensi::with('guru')->orderBy('id_presensi', 'desc');

    //         if ($request->filled('bulan')) {
    //             $query->where('bulan', $request->bulan);
    //         }

    //         return DataTables::of($query)
    //             ->addIndexColumn()
    //             ->editColumn('bulan', function ($row) {
    //                 return formatBulan($row->bulan);
    //             })
    //             ->editColumn('nama_guru', function ($row) {
    //                 return $row->guru ? $row->guru->user->name : '-';
    //             })
    //             ->addColumn('action', function ($row) {
    //                 $editBtn = '<a href="' . route('presensi.edit', $row->id_presensi) . '" class="btn btn-warning text-white ml-2"><i class="fa-solid fa-pen-nib"></i><span class="ml-2 small">Edit</span></a>';
    //                 $deleteBtn = '<form id="delete-form-' . $row->id_presensi . '" action="' . route('presensi.destroy', $row->id_presensi) . '" method="POST" style="display:inline;">
    //                 ' . csrf_field() . '
    //                 ' . method_field('DELETE') . '
    //                 <button type="button" onclick="deleteJabatan(' . $row->id_presensi . ')" class="btn btn-danger ml-2">
    //                     <i class="fa-solid fa-trash"></i><span class="ml-2 small">Hapus</span>
    //                 </button>
    //             </form>';

    //                 return '<div class="text-center">' . $editBtn . $deleteBtn . '</div>';
    //             })
    //             ->rawColumns(['action'])
    //             ->make(true);
    //     }
    //     return view('presensi.index');
    // }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $guru = Guru::with('user')->get();
        return view('presensi.create', compact('guru'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'bulan' => 'required',
            'id_guru' => 'required',
            'hadir' => 'required|numeric',
            'sakit' => 'required|numeric',
            'alpha' => 'required|numeric',
        ]);

        $simpan = Presensi::create([
            'bulan' => $request->bulan,
            'id_guru' => $request->id_guru,
            'hadir' => $request->hadir,
            'sakit' => $request->sakit,
            'alpha' => $request->alpha
        ]);

        if ($simpan) {
            session()->flash('berhasil', 'Presensi berhasil disimpan!');
            return redirect()->route('presensi.index');
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
    public function laporanPresensiIndex()
    {
        return view('laporan.lap-presensi');
    }

    public function laporanPresensiCetak(Request $request)
    {
        $bulan = $request->bulan;
        $semuaPresensi = Presensi::where('bulan', $bulan)->get();
        $fileName = 'Laporan-presensi-' . formatBulan($bulan) . '.pdf';
        $pdf = Pdf::loadView('laporan.lap-presensi-cetak', [
            'bulan' => $bulan,
            'semuaPresensi' => $semuaPresensi,
        ])->setPaper('A4', 'portrait');

        return $pdf->stream($fileName);
    }

    public function getPresensiJson(Request $request)
    {
        $id_guru = $request->id_guru;
        $bulan = $request->bulan;

        $presensi = Presensi::where('id_guru', $id_guru)
            ->where('bulan', $bulan)
            ->first();
        
            

        if ($presensi) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'hadir' => $presensi->hadir,
                    'sakit' => $presensi->sakit,
                    'alpha' => $presensi->alpha,
                ]
            ]);
        } else {
            return response()->json([
                'status' => 'not_found'
            ]);
        }
    }
}