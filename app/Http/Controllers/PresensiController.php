<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;

class PresensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Presensi::with('guru.user');

            // Filter berdasarkan bulan
            if ($request->filled('bulan')) {
                $query->whereRaw('SUBSTRING(TRIM(bulan), 6, 2) = ?', [$request->bulan]);
            }

            // Filter berdasarkan tahun
            if ($request->filled('tahun')) {
                $query->whereRaw('SUBSTRING(TRIM(bulan), 1, 4) = ?', [$request->tahun]);
            }

            // Filter berdasarkan nama guru
            if ($request->filled('nama')) {
                $query->whereHas('guru.user', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->nama . '%');
                });
            }

            return DataTables::of($query)
                ->addIndexColumn()

                // Format bulan
                ->editColumn('bulan', function ($row) {
                    return formatBulan($row->bulan);
                })

                // Filter bulan custom
                ->filterColumn('bulan', function ($query, $keyword) {
                    $englishMonths = indoToEnglishMonth($keyword);
                    $numeric = trim($keyword);

                    $query->where(function ($q) use ($englishMonths, $numeric) {
                        foreach ($englishMonths as $month) {
                            $q->orWhereRaw("LOWER(MONTHNAME(bulan)) LIKE ?", ["%" . strtolower($month) . "%"]);
                        }
                        if (preg_match('/^\d{4}$/', $numeric)) {
                            $q->orWhereYear('bulan', $numeric);
                        }
                    });
                })

                // Tampilkan nama guru
                ->editColumn('id_guru', function ($row) {
                    return $row->guru && $row->guru->user ? $row->guru->user->name : '-';
                })

                // Kolom action
                ->addColumn('action', function ($row) {
                    $editBtn = '<a href="' . route('presensi.edit', $row->id_presensi) . '" class="btn btn-warning text-white ml-2"><i class="fa-solid fa-pen-nib"></i><span class="ml-2">Edit</span></a>';
                    $deleteBtn = '<form id="delete-form-' . $row->id_presensi . '" action="' . route('presensi.destroy', $row->id_presensi) . '" method="POST" style="display:inline;">
                    ' . csrf_field() . '
                    ' . method_field('DELETE') . '
                    <button type="button" onclick="deletePresensi(' . $row->id_presensi . ')" class="btn btn-danger">
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

        // Ambil daftar nama guru
        $list_nama = Guru::with('user')->get()->map(function ($guru) {
            return $guru->user ? $guru->user->name : null;
        })
            ->filter()
            ->unique()
            ->sort() // urutkan berdasarkan abjad
            ->values(); // reset index

        return view('presensi.index', compact('list_bulan', 'list_tahun', 'list_nama'));
    }


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
            'tidak_hadir' => 'required|numeric',
        ]);

        $simpan = Presensi::create([
            'bulan' => $request->bulan,
            'id_guru' => $request->id_guru,
            'hadir' => $request->hadir,
            'sakit' => $request->sakit,
            'tidak_hadir' => $request->tidak_hadir
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
        $data = Presensi::find($id);
        $guru = Guru::with('user')->get();
        return view('presensi.edit', compact('data', 'guru'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'bulan' => 'required',
            'id_guru' => 'required',
            'hadir' => 'required|numeric',
            'sakit' => 'required|numeric',
            'tidak_hadir' => 'required|numeric',
        ]);

        $update = Presensi::find($id)->update([
            'bulan' => $request->bulan,
            'id_guru' => $request->id_guru,
            'hadir' => $request->hadir,
            'sakit' => $request->sakit,
            'tidak_hadir' => $request->tidak_hadir
        ]);

        if ($update) {
            session()->flash('berhasil', 'Presensi berhasil diupdate!');
            return redirect()->route('presensi.index');
        } else {
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $hapus = Presensi::where('id_presensi', $id)->delete();
        if ($hapus) {
            session()->flash('berhasil', 'Presensi berhasil dihapus!');
            return redirect()->route('presensi.index');
        } else {
            return redirect()->back();
        }
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

    public function cekPresensi(Request $request)
    {
        $id_guru = $request->id_guru;
        $bulans = Presensi::where('id_guru', $id_guru)
            ->pluck('bulan');

        return response()->json([
            'status' => 'success',
            'bulans' => $bulans
        ]);
    }
}
