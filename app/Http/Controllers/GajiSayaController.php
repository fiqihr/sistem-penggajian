<?php

namespace App\Http\Controllers;

use App\Models\Gaji;
use App\Models\Guru;
use App\Models\PotonganGaji;
use App\Models\Presensi;
use App\Models\Tunjangan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Str;

class GajiSayaController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index(Request $request)
    {
        $id_user = Auth::user()->id;
        $id_guru = Guru::where('id_user', $id_user)->value('id_guru');

        if ($request->ajax()) {
            $query = Gaji::query()->where('id_guru', $id_guru)->whereNot('status', 'belum');

            // 🔍 Pencarian global termasuk nama bulan
            if ($request->has('search') && !empty($request->search['value'])) {
                $searchValue = strtolower($request->search['value']);

                $bulanMap = [
                    'januari' => '01',
                    'februari' => '02',
                    'maret' => '03',
                    'april' => '04',
                    'mei' => '05',
                    'juni' => '06',
                    'juli' => '07',
                    'agustus' => '08',
                    'september' => '09',
                    'oktober' => '10',
                    'november' => '11',
                    'desember' => '12',
                ];

                $bulanDitemukan = false;

                foreach ($bulanMap as $nama => $angka) {
                    if (Str::contains($searchValue, $nama)) {
                        // Ambil tahun jika ada angka (misal: "juni 2025")
                        $tahun = preg_replace('/[^0-9]/', '', $searchValue);

                        if (!empty($tahun)) {
                            $pattern = "$tahun-$angka";
                        } else {
                            $pattern = "-$angka";
                        }

                        $query->where('bulan', 'like', "%$pattern%");
                        $bulanDitemukan = true;
                        break;
                    }
                }

                // Jika tidak ditemukan nama bulan, lanjut pencarian umum
                if (!$bulanDitemukan) {
                    $query->where(function ($q) use ($searchValue) {
                        $q->where('bulan', 'like', "%$searchValue%")
                            ->orWhere('total_gaji', 'like', "%$searchValue%")
                            ->orWhere('status', 'like', "%$searchValue%");
                    });
                }
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('bulan_format', function ($row) {
                    return formatBulan($row->bulan);
                })
                ->editColumn('bulan', function ($row) {
                    return formatBulan($row->bulan);
                })
                ->editColumn('total_gaji', function ($row) {
                    return formatRupiah($row->total_gaji);
                })
                ->addColumn('action', function ($row) {
                    // tombol aksi
                })
                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && !empty($request->search['value'])) {
                        $searchValue = strtolower($request->search['value']);

                        $bulanMap = [
                            'januari' => '01',
                            'februari' => '02',
                            'maret' => '03',
                            'april' => '04',
                            'mei' => '05',
                            'juni' => '06',
                            'juli' => '07',
                            'agustus' => '08',
                            'september' => '09',
                            'oktober' => '10',
                            'november' => '11',
                            'desember' => '12',
                        ];

                        foreach ($bulanMap as $nama => $angka) {
                            if (Str::contains($searchValue, $nama)) {
                                $tahun = preg_replace('/[^0-9]/', '', $searchValue);
                                $pattern = !empty($tahun) ? "$tahun-$angka" : "-$angka";
                                $query->where('bulan', 'like', "%$pattern%");
                                return;
                            }
                        }

                        $query->where(function ($q) use ($searchValue) {
                            $q->where('bulan', 'like', "%$searchValue%")
                                ->orWhere('total_gaji', 'like', "%$searchValue%")
                                ->orWhere('status', 'like', "%$searchValue%");
                        });
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('gaji_saya.index');
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
    public function show(string $encryptedId)
    {
        try {
            $id = Crypt::decryptString($encryptedId);
        } catch (DecryptException $e) {
            abort(404, 'Data gaji tidak ditemukan atau tautan tidak valid.');
        }

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
        //
    }

    public function cekKode(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'kode' => 'required|string',
        ]);

        $gaji = Gaji::find($request->id);

        if (!$gaji) {
            return response()->json(['message' => 'Data gaji tidak ditemukan.'], 404);
        }

        // Cek apakah status dikirim dan kode sesuai dan belum kadaluarsa
        if (
            $gaji->status !== 'belum' &&
            $gaji->kode_akses === $request->kode &&
            now()->lt($gaji->kode_akses_expired)
        ) {
            $encryptedIdGaji = Crypt::encryptString($gaji->id_gaji);
            return response()->json([
                'success' => true,
                'message' => 'Kode valid!',
                'encrypted_id' => $encryptedIdGaji,
            ]);
        } else {
            return response()->json([
                'message' => 'Kode salah atau sudah kadaluarsa.'
            ], 403);
        }
    }
}