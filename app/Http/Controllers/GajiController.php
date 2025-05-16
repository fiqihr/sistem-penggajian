<?php

namespace App\Http\Controllers;

use App\Models\Gaji;
use App\Models\Guru;
use App\Models\Jabatan;
use App\Models\PotonganGaji;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class GajiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        App::setLocale('id');
        if ($request->ajax()) {
            return DataTables::of(Gaji::query()->orderBy('id_gaji', 'desc'))
                ->addIndexColumn()
                ->editColumn('bulan', function ($row) {
                    return formatBulan($row->bulan);
                })
                ->editColumn('id_guru', function ($row) {
                    return $row->guru->user->name;
                })
                ->editColumn('jabatan', function ($row) {
                    return $row->guru->jabatan->nama_jabatan;
                })
                ->editColumn('gaji_pokok', function ($row) {
                    return formatRupiah($row->guru->jabatan->gaji_pokok);
                })
                ->editColumn('tj_transport', function ($row) {
                    return formatRupiah($row->guru->jabatan->tj_transport);
                })
                ->editColumn('uang_makan', function ($row) {
                    return formatRupiah($row->guru->jabatan->uang_makan);
                })
                ->editColumn('potongan', function ($row) {
                    return formatRupiah($row->potongan);
                })
                ->editColumn('total_gaji', function ($row) {
                    return formatRupiah($row->total_gaji);
                })
                ->addColumn('action', function ($row) {
                    // $showBtn = '<a href="' . route('potongan-gaji.show', $row->id_potongan_gaji) . '" class="btn btn-primary btn-user text-white"><i class="fa-solid fa-eye"></i><span class="ml-2">Detail</span></a>';
                    $showBtn = '<a href="' . route('gaji.show', $row->id_gaji) . '" class="ml-2 btn btn-primary text-white d-flex align-items-center"><i class="fa-solid fa-note-sticky"></i><span class="ml-2">Lihat</span></a>';
                    // $editBtn = '<a href="' . route('potongan-gaji.edit', $row->id_potongan_gaji) . '" class="ml-2 btn btn-warning text-white"><i class="fa-solid fa-pen-nib"></i><span class="ml-2">Edit</span></a>';
                    // $deleteBtn = '<form id="delete-form-' . $row->id_gaji . '" action="' . route('gaji.destroy', $row->id_gaji) . '" method="POST" style="display:inline;">
                    //     ' . csrf_field() . '
                    //     ' . method_field('DELETE') . '
                    //     <button type="button" onclick="deleteGaji(' . $row->id_gaji . ')" class="btn btn-danger">
                    //         <i class="fa-solid fa-trash"></i><span class="ml-2 ">Hapus</span>
                    //     </button>
                    // </form>';

                    //                 $kirimBtn = '
                    // <form id="kirim-form-' . $row->id_gaji . '" action="' . route('gaji.kirim', $row->id_gaji) . '" method="POST" style="display: inline;">
                    //     ' . csrf_field() . '
                    //     <button type="button" onclick="kirimGaji(' . $row->id_gaji . ')" class="ml-2 btn btn-warning text-white">
                    //         <i class="fas fa-hand-holding-usd"></i><span class="ml-2">Kirim</span>
                    //     </button>
                    // </form>';
                    $cekStatusGaji = $row->status;
                    if ($cekStatusGaji == 'belum') {
                        $kirimBtn = '<button onclick="kirimGaji(' . $row->id_gaji . ')" class="ml-2 btn btn-warning text-white d-flex align-items-center"><i class="fa-solid fa-money-check-dollar"></i><span class="ml-2">Kirim</span></button>';
                    } elseif ($cekStatusGaji == 'dikirim') {
                        $kirimBtn = '<button class="ml-2 btn btn-info text-white d-flex align-items-center"><i class="fa-solid fa-hand-holding-dollar"></i><span class="ml-2">Dikirim</span></button>';
                    } else {
                        $kirimBtn = '<button class="ml-2 btn btn-success text-white d-flex align-items-center"><i class="fa-solid fa-check"></i><span class="ml-2">Selesai</span></button>';
                    }


                    return '<div class="text-center d-flex">' . $showBtn . $kirimBtn .  '</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('gaji.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $semua_guru = Guru::with('user')->get();
        return view('gaji.create', compact('semua_guru'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $id_guru = $request->id_guru;
        $gaji = Guru::where('id_guru', $id_guru)->first()->jabatan;

        $gaji_pokok = $gaji->gaji_pokok;
        $tj_transport = $gaji->tj_transport;
        $uang_makan = $gaji->uang_makan;

        $jumlahGaji = $gaji_pokok + $tj_transport + $uang_makan;

        $bulan_presensi = $request->bulan;
        $presensi = Presensi::where('bulan', $bulan_presensi)->where('id_guru', $id_guru)->first();

        $sakit = $presensi->sakit;
        $alpha = $presensi->alpha;

        $potonganAlpha = PotonganGaji::where('nama_potongan', 'Alpha')->first()->jml_potongan;
        $potonganSakit = PotonganGaji::where('nama_potongan', 'Sakit')->first()->jml_potongan;

        $jmlPotonganAlpha = $potonganAlpha * $alpha;
        $jmlPotonganSakit = $potonganSakit * $sakit;

        $totalPotongan = $jmlPotonganAlpha + $jmlPotonganSakit;
        $totalGaji = $jumlahGaji - $totalPotongan;

        $simpan = Gaji::create([
            'bulan' => $request->bulan,
            'id_guru' => $request->id_guru,
            'potongan' => $totalPotongan,
            'total_gaji' => $totalGaji
        ]);
        if ($simpan) {
            session()->flash('berhasil', 'Gaji Guru berhasil dicetak!');
            return redirect()->route('gaji.index');
        } else {
            return redirect()->back();
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $gaji = Gaji::with('guru', 'jabatan')->where('id_gaji', $id)->first();

        $bulan_presensi = $gaji->bulan;
        $id_guru = $gaji->id_guru;
        $presensi = Presensi::where('bulan', $bulan_presensi)->where('id_guru', $id_guru)->first();

        $sakit = $presensi->sakit ?? 0;
        $alpha = $presensi->alpha ?? 0;

        $potonganAlpha = PotonganGaji::where('nama_potongan', 'Alpha')->first()->jml_potongan;
        $potonganSakit = PotonganGaji::where('nama_potongan', 'Sakit')->first()->jml_potongan;

        $jmlPotonganAlpha = $potonganAlpha * $alpha;
        $jmlPotonganSakit = $potonganSakit * $sakit;

        if (Auth::user()->hak_akses == 'guru') {
            Gaji::where('id_gaji', $id)->update(['status' => 'diterima']);
        }

        // dd($alpha, $sakit, $jmlPotonganAlpha, $jmlPotonganSakit);

        $pdf = Pdf::loadView('gaji.slip', [
            'gaji' => $gaji,
            'alpha' => $alpha,
            'sakit' => $sakit,
            'jmlPotonganAlpha' => $jmlPotonganAlpha,
            'jmlPotonganSakit' => $jmlPotonganSakit,
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('Slip-Gaji.pdf');
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
        $hapus = Gaji::where('id_gaji', $id)->delete();
        if ($hapus) {
            session()->flash('berhasil', 'Gaji berhasil dihapus!');
            return redirect()->route('gaji.index');
        } else {
            return redirect()->back();
        }
    }

    public function kirimGaji(string $id)
    {
        $kirim = Gaji::where('id_gaji', $id)->update(['status' => 'dikirim']);
        if ($kirim) {
            return response()->json(['message' => 'Slip Gaji Guru berhasil dikirim!']);
        } else {
            return response()->json(['message' => 'Gagal mengirim slip gaji.'], 500);
        }
    }

    public function laporanGajiIndex()
    {
        return view('laporan.lap-gaji');
    }

    public function laporanGajiCetak(Request $request)
    {
        $bulan = $request->bulan;
        $semuaGaji = Gaji::where('bulan', $bulan)->get();
        $fileName = 'Laporan-gaji-' . formatBulan($bulan) . '.pdf';
        // dd($semuaGaji);
        $pdf = Pdf::loadView('laporan.lap-gaji-cetak', [
            'bulan' => $bulan,
            'semuaGaji' => $semuaGaji,
        ])->setPaper('A4', 'portrait');

        return $pdf->stream($fileName);
    }
}