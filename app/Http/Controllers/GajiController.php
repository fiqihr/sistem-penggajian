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
        return view('gaji.index');
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

    // public function store(Request $request)
    // {
    //     $id_guru = $request->id_guru;
    //     $gaji = Guru::where('id_guru', $id_guru)->first()->jabatan;

    //     $gaji_pokok = $gaji->gaji_pokok;
    //     $tj_transport = $gaji->tj_transport;
    //     $uang_makan = $gaji->uang_makan;

    //     $jumlahGaji = $gaji_pokok + $tj_transport + $uang_makan;

    //     $bulan_presensi = $request->bulan;
    //     $presensi = Presensi::where('bulan', $bulan_presensi)->where('id_guru', $id_guru)->first();

    //     $sakit = $presensi->sakit;
    //     $alpha = $presensi->alpha;

    //     $potonganAlpha = PotonganGaji::where('nama_potongan', 'Alpha')->first()->jml_potongan;
    //     $potonganSakit = PotonganGaji::where('nama_potongan', 'Sakit')->first()->jml_potongan;

    //     $jmlPotonganAlpha = $potonganAlpha * $alpha;
    //     $jmlPotonganSakit = $potonganSakit * $sakit;

    //     $totalPotongan = $jmlPotonganAlpha + $jmlPotonganSakit;
    //     $totalGaji = $jumlahGaji - $totalPotongan;

    //     $simpan = Gaji::create([
    //         'bulan' => $request->bulan,
    //         'id_guru' => $request->id_guru,
    //         'potongan' => $totalPotongan,
    //         'total_gaji' => $totalGaji
    //     ]);
    //     if ($simpan) {
    //         session()->flash('berhasil', 'Gaji Guru berhasil dicetak!');
    //         return redirect()->route('gaji.index');
    //     } else {
    //         return redirect()->back();
    //     }
    // }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $gaji = Gaji::with('guru', 'jabatan')->where('id_gaji', $id)->first();

        $bulan_presensi = $gaji->bulan;
        $id_guru = $gaji->id_guru;
        $id_tunjangan = $gaji->id_tunjangan;
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
        // $potongan_bpr = PotonganGaji::where('nama_potongan', 'BPR')->first()->jml_potongan;
        // $potongan_lazisnu = PotonganGaji::where('nama_potongan', 'Lazisnu')->first()->jml_potongan;

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

        // dd($alpha, $sakit, $jmlPotonganAlpha, $jmlPotonganSakit);
        $bulan_gaji = $gaji->bulan;
        $nama_guru = $gaji->guru->user->name;

        $pdf = Pdf::loadView('gaji.slip', [
            'gaji' => $gaji,
            'tidak_hadir' => $tidak_hadir,
            'sakit' => $sakit,
            'potongan_tidak_hadir' => $potongan_tidak_hadir,
            'potongan_sakit' => $potongan_sakit,
            // 'potongan_bpr' => $potongan_bpr,
            // 'potongan_lazisnu' => $potongan_lazisnu,
            'tidak_hadir' => $tidak_hadir,
            'sakit' => $sakit,
            'jml_tunjangan' => $jml_tunjangan,
            'nama_tunjangan' => $nama_tunjangan,
            'total_bruto' => $total_bruto,
            'potongan_sakit_dan_tidak_hadir' => $potongan_sakit_dan_tidak_hadir,
            'total_potongan' => $total_potongan,
            'semua_jenis_potongan' => $semua_jenis_potongan,
        ])->setPaper('A4', 'portrait');

        $file_name = 'Slip Gaji - ' . formatBulan($bulan_gaji) . ' - ' . $nama_guru . '.pdf';
        return $pdf->stream($file_name);
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

    // $kode = Str::random(6); // misalnya kode acak 6 karakter
    //         $expired = now()->addHours(24); // kadaluarsa 24 jam dari sekarang

    //         $gaji->kode_akses = $kode;
    //         $gaji->kode_akses_expired = $expired;
    //         $gaji->save();

    //         // Kirim email ke guru
    //         Mail::to($gaji->guru->user->email)->send(new SlipGajiEmail($kode, formatBulan($gaji->bulan)));

    // public function kirimGaji(string $id)
    // {
    //     $kirim = Gaji::where('id_gaji', $id)->update(['status' => 'dikirim']);
    //     if ($kirim) {
    //         return response()->json(['message' => 'Slip Gaji Guru berhasil diserahkan!']);
    //     } else {
    //         return response()->json(['message' => 'Gagal mengirim slip gaji.'], 500);
    //     }
    // }

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

        // kirim email
        try {
            Mail::to($gaji->guru->user->email)->send(new KirimKodeSlipGaji($gaji->guru, $kode, $gaji->bulan));
            return response()->json(['message' => 'Slip Gaji Guru berhasil diserahkan!']);
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal mengirim slip gaji.'], 500);
        }
        // Mail::to($gaji->guru->user->email)->send(new KirimKodeSlipGaji($gaji->guru, $kode));

        // return response()->json(['message' => 'Slip Gaji Guru berhasil diserahkan!']);
        // if ($kirim_email) {
        //     return response()->json(['message' => 'Slip Gaji Guru berhasil diserahkan!']);
        // } else {
        //     return response()->json(['message' => 'Gagal mengirim slip gaji.'], 500);
        // }
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
        // $potongan_bpr = PotonganGaji::where('nama_potongan', 'BPR')->first()->jml_potongan;
        // $potongan_lazisnu = PotonganGaji::where('nama_potongan', 'Lazisnu')->first()->jml_potongan;

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
        // $

        // dd($nama_guru, $gaji_pokok, $jml_tunjangan);
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
