<x-layout title="Presensi">
    <div class="mt-5 mb-4">
        <h3 class="">Presensi</h3>
        <p class="small font-italic">Data Presensi</p>
    </div> {{-- FILTER BULAN --}}
    <div class="py-4">
        <div class="d-flex justify-content-between align-items-end">
            <div class="bg-white px-4 py-3 rounded-lg shadow-sm">
                <p class="block font-weight-bold ">Filter</p>
                <div class="row">
                    <div class="col-4">
                        <select id="filter-bulan" class="form-control">
                            <option value="">-- Semua Bulan --</option>
                            @foreach ($list_bulan as $bulan)
                                <option value="{{ str_pad($bulan, 2, '0', STR_PAD_LEFT) }}">
                                    {{ formatNamaBulan($bulan) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-4">
                        <select id="filter-tahun" class="form-control">
                            <option value="">-- Semua Tahun --</option>
                            @foreach ($list_tahun as $tahun)
                                <option value="{{ $tahun }}">{{ $tahun }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-4">
                        <select name="nama" id="filter-nama" class="form-control">
                            <option value="">-- Semua Guru --</option>
                            @foreach ($list_nama as $nama)
                                <option value="{{ $nama }}">{{ $nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="align-items-end">
                <div class="mb-2 d-flex justify-content-end">
                    <a href="{{ route('presensi.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i><span class=" ml-2">Isi Presensi</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid bg-white rounded-lg p-4 shadow-sm mb-5">
        <table id="my-table" class="table table-bordered table-striped small w-100 text-gray-800">
            <thead id="mytable-thead">
                <tr>
                    <th class="text-center">No</th>
                    <th>Id Presensi</th>
                    <th>Bulan</th>
                    <th>Nama Guru</th>
                    <th class="text-center">Hadir</th>
                    <th class="text-center">Sakit</th>
                    <th class="text-center">Tidak Hadir</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    @push('scripts')
        <script>
            const presensiRoute = "{{ route('presensi.index') }}";
            const presensiMessage = {!! json_encode(session('berhasil')) !!};
        </script>
        <script src="{{ asset('libs/js/presensi.js') }}"></script>
    @endpush
</x-layout>
