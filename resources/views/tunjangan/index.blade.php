<x-layout>
    <h3 class="mt-5 mb-4">Tunjangan</h3>

    {{-- <div class="mb-2 d-flex justify-content-end">
        <a href="{{ route('potongan-gaji.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i><span class=" ml-2">Tambah Potongan Gaji</span>
        </a>
    </div> --}}

    <div class="container-fluid bg-white rounded-lg p-4 shadow-sm mb-5">
        <table id="my-table" class="table table-bordered table-striped small w-100">
            <thead id="mytable-thead">
                <tr>
                    <th class="text-center">No</th>
                    <th>Bulan</th>
                    <th>Nama Guru</th>
                    <th>Jumlah Tunjangan</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    @push('scripts')
        <script>
            const tunjanganRoute = "{{ route('tunjangan.index') }}";
            const tunjanganMessage = {!! json_encode(session('berhasil')) !!};
        </script>
        <script src="{{ asset('libs/js/tunjangan.js') }}"></script>
    @endpush
</x-layout>
