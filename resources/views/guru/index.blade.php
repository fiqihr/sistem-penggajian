<x-layout title="Guru">
    <div class="mt-5 mb-4">
        <h3 class="">Guru</h3>
        <p class="small font-italic">Data Guru</p>
    </div>
    <div class="mb-2 d-flex justify-content-end">
        <a href="{{ route('guru.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i><span class=" ml-2">Tambah guru</span>
        </a>
    </div>

    <div class="container-fluid bg-white rounded-lg p-4 shadow-sm mb-5">
        <table id="my-table" class="table table-bordered table-striped small w-100 text-gray-800">
            <thead id="mytable-thead">
                <tr>
                    <th class="text-center">No</th>
                    <th>Nama</th>
                    <th>NIG</th>
                    <th>Jenis Kelamin</th>
                    <th>Nama Jabatan</th>
                    <th>Tanggal Masuk</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    @push('scripts')
        <script>
            const guruRoute = "{{ route('guru.index') }}";
            const guruMessage = {!! json_encode(session('berhasil')) !!};
        </script>
        <script src="{{ asset('libs/js/guru.js') }}"></script>
    @endpush
</x-layout>
