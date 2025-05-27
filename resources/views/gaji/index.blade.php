<x-layout title="Gaji Guru">
    <div class="mt-5 mb-4">
        <h3 class="">Gaji Guru</h3>
        <p class="small font-italic">Data Gaji</p>
    </div>
    <div class="py-4">
        <div class="d-flex justify-content-between align-items-end">
            <div class="bg-white px-4 py-3 rounded-lg shadow-sm">
                <p class="block font-weight-bold ">Filter</p>
                <div class="row">
                    <div class="col-6">
                        <select id="filter-bulan" class="form-control">
                            <option value="">-- Semua Bulan --</option>
                            @foreach ($list_bulan as $bulan)
                                <option value="{{ str_pad($bulan, 2, '0', STR_PAD_LEFT) }}">
                                    {{ formatNamaBulan($bulan) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <select id="filter-tahun" class="form-control">
                            <option value="">-- Semua Tahun --</option>
                            @foreach ($list_tahun as $tahun)
                                <option value="{{ $tahun }}">{{ $tahun }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="align-items-end">
                <div class="mb-2 d-flex justify-content-end">
                    <a href="{{ route('gaji.create') }}" class="btn btn-info">
                        <i class="fa-solid fa-clipboard-list"></i><span class=" ml-2">Buat Slip Gaji</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid bg-white rounded-lg p-4 shadow-sm mb-5">
        <table id="my-table" class="table table-bordered table-striped small w-100 text-nowrap text-gray-800">
            <thead id="mytable-thead">
                <tr>
                    <th class="text-center">No</th>
                    <th>Id Gaji</th>
                    <th>Bulan</th>
                    <th>Nama Guru</th>
                    <th>Jabatan</th>
                    <th>Gaji Pokok</th>
                    <th>Tunjangan</th>
                    <th>Potongan</th>
                    <th>Total Gaji</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    @push('scripts')
        <script>
            const gajiRoute = "{{ route('gaji.index') }}";
            const gajiMessage = {!! json_encode(session('berhasil')) !!};
            const gajiKirim = "{{ route('gaji.kirim', ':id') }}";
        </script>
        <script src="{{ asset('libs/js/gaji.js') }}"></script>
    @endpush
</x-layout>
