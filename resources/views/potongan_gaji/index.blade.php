<x-layout title="Potongan Gaji">
    <div class="mt-5 mb-4">
        <h3 class="">Potongan Gaji</h3>
        <p class="small font-italic">Data Potongan Gaji</p>
    </div>

    <div class="mb-2 d-flex justify-content-end">
        <a href="{{ route('potongan-gaji.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i><span class=" ml-2">Tambah Potongan Gaji</span>
        </a>
    </div>

    <div class="container-fluid bg-white rounded-lg p-4 shadow-sm mb-5">
        <table id="my-table" class="table table-bordered table-striped small w-100 text-gray-800">
            <thead id="mytable-thead">
                <tr>
                    <th class="text-center">No</th>
                    <th>Potongan Gaji</th>
                    <th>Jumlah</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    @push('scripts')
        <script>
            const potonganGajiRoute = "{{ route('potongan-gaji.index') }}";
            const potonganGajiMessage = {!! json_encode(session('berhasil')) !!};
        </script>
        <script src="{{ asset('libs/js/potongan_gaji.js') }}"></script>
    @endpush
</x-layout>
