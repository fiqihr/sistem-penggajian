<x-layout title="Detail Slip Gaji">
    <div class="mt-5 mb-4">
        <h3 class="">Detail Slip Gaji</h3>
        <p class="small font-italic">Data Gaji &rsaquo; Cetak Slip Gaji &rsaquo; Detail Slip Gaji</p>
    </div>
    <div class="container-fluid  bg-white rounded-lg p-4 shadow-sm">
        <form action="{{ route('gaji.store') }}" method="POST">
            <input type="hidden" name="id_guru" value="{{ $id_guru }}">
            <input type="hidden" name="bulan" value="{{ $bulan }}">
            <input type="hidden" name="potongan" value="{{ $total_potongan }}">
            <input type="hidden" name="total_gaji" value="{{ $total_gaji }}">
            <input type="hidden" name="id_tunjangan" value="{{ $id_tunjangan }}">
            @csrf
            <table class="table">
                <tbody>
                    <tr>
                        <td style="width: 30%">Nama Guru</td>
                        <td style="width: 10%">:</td>
                        <td>{{ $nama_guru }}</td>
                    </tr>
                    <tr>
                        <td>Gaji Pokok</td>
                        <td>:</td>
                        <td>{{ formatRupiah($gaji_pokok) }}</td>
                    </tr>
                    <tr>
                        <td>Tunjangan</td>
                        <td>:</td>
                        <td>{{ formatRupiah($jml_tunjangan) }}</td>
                    </tr>
                    <tr>
                        <td>Total Bruto</td>
                        <td>:</td>
                        <td><strong>{{ formatRupiah($total_bruto) }}</strong></td>
                    </tr>
                    <tr>
                        <td><strong>Potongan:</strong></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Tidak Hadir ({{ $tidak_hadir }} hari)</td>
                        <td>:</td>
                        <td>{{ $tidak_hadir }} x {{ formatRupiah($potongan_tidak_hadir) }}</td>
                    </tr>
                    <tr>
                        <td>Sakit ({{ $sakit }} hari)</td>
                        <td>:</td>
                        <td>{{ $sakit }} x {{ formatRupiah($potongan_sakit) }}</td>
                    </tr>
                    @foreach ($semua_jenis_potongan as $potongan)
                        <tr>
                            <td>{{ $potongan->nama_potongan }}</td>
                            <td>:</td>
                            <td>{{ formatRupiah($potongan->jml_potongan) }}</td>
                        </tr>
                    @endforeach
                    {{-- <tr>
                        <td>BPR</td>
                        <td>:</td>
                        <td>{{ formatRupiah($potongan_bpr) }}</td>
                    </tr>
                    <tr>
                        <td>Lazisnu</td>
                        <td>:</td>
                        <td>{{ formatRupiah($potongan_lazisnu) }}</td>
                    </tr> --}}
                    <tr>
                        <td>Total Potongan</td>
                        <td>:</td>
                        <td><strong>{{ formatRupiah($total_potongan) }}</strong></td>
                    </tr>
                    <tr>
                        <td><strong>Total Gaji</strong></td>
                        <td>:</td>
                        <td><strong>{{ formatRupiah($total_gaji) }}</strong></td>
                    </tr>
                </tbody>
            </table>
            <hr class="mb-4 mt-4">
            <div class="d-flex justify-content-end">
                <button type="submit" class=" btn btn-success"><i class="fa-solid fa-floppy-disk"></i>
                    <span class="ml-1">Simpan dan Cetak</span>
                </button>
            </div>
        </form>
    </div>

</x-layout>
