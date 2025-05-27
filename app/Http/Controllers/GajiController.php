<?php

namespace App\Http\Controllers;

use App\Models\Gaji;
use App\Models\Guru;
use App\Models\Jabatan;
use App\Models\PotonganGaji;
use App\Models\Presensi;
use App\Models\Tunjangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\KirimKodeSlipGaji;
use Illuminate\Support\Facades\DB;

class GajiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        App::setLocale('id');
        if ($request->ajax()) {
            $query = Gaji::with(['guru.jabatan', 'guru.user', 'tunjangan'])
                ->leftJoin('guru', 'gaji.id_guru', '=', 'guru.id_guru')
                ->leftJoin('users', 'guru.id_user', '=', 'users.id')
                ->leftJoin('jabatan', 'guru.id_jabatan', '=', 'jabatan.id_jabatan')
                ->select('gaji.*', DB::raw('users.name as nama_guru'), DB::raw('jabatan.gaji_pokok as gaji_pokok'));
            if ($request->filled('bulan')) {
                $query->whereRaw('SUBSTRING(TRIM(bulan), 6, 2) = ?', [$request->bulan]);
            }
            if ($request->filled('tahun')) {
                $query->whereRaw('SUBSTRING(TRIM(bulan), 1, 4) = ?', [$request->tahun]);
            }
            return DataTables::of($query)
                ->filterColumn('id_jabatan', function ($query, $keyword) {
                    $query->whereHas('guru.jabatan', function ($q) use ($keyword) {
                        $q->where('nama_jabatan', 'like', "%$keyword%");
                    });
                })
                ->filterColumn('id_guru', function ($query, $keyword) {
                    $query->whereHas('guru.user', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%$keyword%");
                    });
                })
                ->filterColumn('id_tunjangan', function ($query, $keyword) {
                    $query->whereHas('tunjangan', function ($q) use ($keyword) {
                        $q->where('nama_tunjangan', 'like', "%$keyword%");
                    });
                })
                ->filterColumn('gaji_pokok', function ($query, $keyword) {
                    $query->whereHas('guru.jabatan', function ($q) use ($keyword) {
                        $q->where('gaji_pokok', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('nama_guru', function ($query, $keyword) {
                    $query->whereHas('guru.user', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })
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
                ->orderColumn('nama_guru', function ($query, $order) {
                    $query->orderBy('users.name', $order);
                })
                ->orderColumn('gaji_pokok', function ($query, $order) {
                    $query->orderBy('jabatan.gaji_pokok', $order);
                })
                ->addIndexColumn()
                ->editColumn('bulan', function ($row) {
                    return formatBulan($row->bulan);
                })
                ->editColumn('id_guru', function ($row) {
                    return $row->nama_guru ?? $row->guru->user->name;
                })

                ->editColumn('id_jabatan', function ($row) {
                    return $row->guru->jabatan->nama_jabatan;
                })
                ->editColumn('gaji_pokok', function ($row) {
                    return formatRupiah($row->gaji_pokok);
                })
                ->editColumn('id_tunjangan', function ($row) {
                    $nama_tunjangan = $row->tunjangan->nama_tunjangan ?? 'Tidak ada tunjangan';
                    $jml_tunjangan = $row->tunjangan->jml_tunjangan ?? 0;
                    return formatRupiah($jml_tunjangan) . ' (' . $nama_tunjangan . ')';
                })
                ->editColumn('potongan', function ($row) {
                    return formatRupiah($row->potongan);
                })
                ->editColumn('total_gaji', function ($row) {
                    return formatRupiah($row->total_gaji);
                })
                ->addColumn('action', function ($row) {
                    $showBtn = '<a target="_blank" href="' . route('gaji.show', $row->id_gaji) . '" class="ml-2 btn btn-primary text-white d-flex align-items-center"><i class="fa-solid fa-note-sticky"></i><span class="ml-2">Lihat</span></a>';
                    $cekStatusGaji = $row->status;
                    if ($cekStatusGaji == 'belum') {
                        $kirimBtn = '<button onclick="kirimGaji(' . $row->id_gaji . ')" class="ml-2 btn btn-warning text-white d-flex align-items-center"><i class="fa-solid fa-money-check-dollar"></i><span class="ml-2">Serahkan</span></button>';
                    } elseif ($cekStatusGaji == 'dikirim') {
                        $kirimBtn = '<button class="ml-2 btn btn-info text-white d-flex align-items-center"><i class="fa-solid fa-hand-holding-dollar"></i><span class="ml-2">Diserahkan</span></button>';
                    } else {
                        $kirimBtn = '<button class="ml-2 btn btn-success text-white d-flex align-items-center"><i class="fa-solid fa-check"></i><span class="ml-2">Diterima</span></button>';
                    }
                    return '<div class="text-center d-flex">' . $showBtn . $kirimBtn .  '</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $list_bulan = Presensi::selectRaw('DISTINCT(SUBSTRING(bulan, 6, 2)) as bulan')
            ->orderBy('bulan')
            ->pluck('bulan');

        $list_tahun = Presensi::selectRaw('DISTINCT(SUBSTRING(bulan, 1, 4)) as tahun')
            ->orderBy('tahun')
            ->pluck('tahun');
        return view('gaji.index', compact('list_bulan', 'list_tahun'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $semua_guru = Guru::with('user')->get();

        // $semua_potongan = PotonganGaji::all();
        $semua_tunjangan = Tunjangan::all();
        return view('gaji.create', compact('semua_guru', 'semua_tunjangan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $id_guru = $request->id_guru;
        $bulan = $request->bulan;
        $id_tunjangan = $request->id_tunjangan ?? null;
        $potongan = $request->potongan;
        $total_gaji = $request->total_gaji;

        $simpan_gaji = Gaji::create([
            'bulan' => $bulan,
            'id_guru' => $id_guru,
            'id_tunjangan' => $id_tunjangan,
            'potongan' => $potongan,
            'total_gaji' => $total_gaji,
        ]);

        if ($simpan_gaji) {
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
        $id_guru = $gaji->id_guru;
        $id_tunjangan = $gaji->id_tunjangan;
        $bulan_presensi = $gaji->bulan;

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

        $bulan_gaji = $gaji->bulan;
        $nama_guru = $gaji->guru->user->name;

        $pdf = Pdf::loadView('gaji.slip', [
            'gaji' => $gaji,
            'tidak_hadir' => $tidak_hadir,
            'sakit' => $sakit,
            'potongan_tidak_hadir' => $potongan_tidak_hadir,
            'potongan_sakit' => $potongan_sakit,
            'tidak_hadir' => $tidak_hadir,
            'sakit' => $sakit,
            'jml_tunjangan' => $jml_tunjangan,
            'nama_tunjangan' => $nama_tunjangan,
            'total_bruto' => $total_bruto,
            'potongan_sakit_dan_tidak_hadir' => $potongan_sakit_dan_tidak_hadir,
            'total_potongan' => $total_potongan,
            'semua_jenis_potongan' => $semua_jenis_potongan,
        ])->setPaper('A4', 'portrait');
        $timestamp = date('YmdHis', strtotime($gaji->created_at));

        $file_name = 'Slip Gaji - ' . formatBulan($bulan_gaji) . ' - ' . $nama_guru . ' - ' . $timestamp . '.pdf';
        return $pdf->stream($file_name);
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
        $gaji = Gaji::with('guru.user')->where('id_gaji', $id)->first();

        if (!$gaji) {
            return response()->json(['message' => 'Data gaji tidak ditemukan.'], 404);
        }

        $kode = strtoupper(Str::random(6)); // lkode acak 6 karakter
        $expired_at = now()->addHours(24); // kadaluarsa 24 jam dari sekarang

        $gaji->update([
            'status' => 'dikirim',
            'kode_akses' => $kode,
            'kode_akses_expired' => $expired_at,
        ]);

        try {
            Mail::to($gaji->guru->user->email)->send(new KirimKodeSlipGaji($gaji->guru, $kode, $gaji->bulan));
            return response()->json(['message' => 'Slip Gaji Guru berhasil diserahkan!']);
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email: ' . $e->getMessage());
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

        $pdf = Pdf::loadView('laporan.lap-gaji-cetak', [
            'bulan' => $bulan,
            'semuaGaji' => $semuaGaji,
        ])->setPaper('A4', 'portrait');

        return $pdf->stream($fileName);
    }

    public function detailGaji(Request $request)
    {
        $id_guru = $request->id_guru;
        $nama_guru = Guru::where('id_guru', $id_guru)->first()->user->name;
        $bulan = $request->bulan;
        $format_bulan = formatBulan($bulan);
        $id_tunjangan = $request->id_tunjangan;
        $jml_tunjangan = Tunjangan::find($id_tunjangan)->jml_tunjangan ?? 0;

        $cek_data = Gaji::where('bulan', $bulan)->where('id_guru', $id_guru)->first();
        if ($cek_data) {
            session()->flash('gagal', "Gaji guru {$nama_guru} pada bulan {$format_bulan} sudah dicetak!");
            return redirect()->route('gaji.create');
        }

        $nama_guru = Guru::where('id_guru', $id_guru)->first()->user->name;
        $gaji_pokok = Guru::where('id_guru', $id_guru)->first()->jabatan->gaji_pokok;

        $total_bruto = $gaji_pokok + $jml_tunjangan;
        $presensi = Presensi::where('bulan', $bulan)->where('id_guru', $id_guru)->first();

        $sakit = $presensi->sakit ?? 0;
        $tidak_hadir = $presensi->tidak_hadir ?? 0;

        $potongan_sakit = PotonganGaji::where('nama_potongan', 'Sakit')->first()->jml_potongan;
        $potongan_tidak_hadir = PotonganGaji::where('nama_potongan', 'Tidak Hadir')->first()->jml_potongan;

        $semua_jenis_potongan = PotonganGaji::where('nama_potongan', 'not like', '%sakit%')->where('nama_potongan', 'not like', '%tidak hadir%')->get();
        $cek_jml_potongan = [];
        foreach ($semua_jenis_potongan as $potongan) {
            $cek_jml_potongan[] = $potongan->jml_potongan;
        }
        $foreach_potongan_lain = array_sum($cek_jml_potongan);

        $total_potongan_tidak_hadir = $potongan_tidak_hadir * $tidak_hadir;
        $total_potongan_sakit = $potongan_sakit * $sakit;

        $total_potongan = $total_potongan_tidak_hadir + $total_potongan_sakit + $foreach_potongan_lain;
        $total_gaji = $total_bruto - $total_potongan;

        return view('gaji.detail', compact(
            'id_guru',
            'bulan',
            'nama_guru',
            'gaji_pokok',
            'jml_tunjangan',
            'total_bruto',
            'tidak_hadir',
            'sakit',
            'potongan_sakit',
            'potongan_tidak_hadir',
            'total_potongan',
            'total_gaji',
            'id_tunjangan',
            'semua_jenis_potongan'
        ));
    }
}