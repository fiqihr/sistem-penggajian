<x-layout>
    <h3 class="mt-5 mb-4">Data Gaji Guru</h3>

    <div class="mb-2 d-flex justify-content-end">
        <a href="{{ route('gaji.create') }}" class="btn btn-info">
            <i class="fa-solid fa-clipboard-list"></i><span class=" ml-2">Buat Slip Gaji</span>
        </a>
    </div>

    <div class="container-fluid bg-white rounded-lg p-4 shadow-sm mb-5">
        <table id="my-table" class="table table-bordered table-striped small w-100 text-nowrap text-gray-800">
            <thead id="mytable-thead">
                <tr>
                    <th class="text-center">No</th>
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
