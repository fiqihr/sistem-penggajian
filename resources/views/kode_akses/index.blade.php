<x-layout title="Kode Akses Saya">
    <div class="mt-5 mb-4">
        <h3 class="">Kode Akses</h3>
        <p class="small font-italic">Kode Akses Saya</p>
    </div>
    <div class="container-fluid bg-white rounded-lg p-4 shadow-sm mb-5">
        <table id="my-table" class="table table-bordered table-striped small w-100">
            <thead id="mytable-thead">
                <tr>
                    <th class="text-center">No</th>
                    <th>Keterangan</th>
                    <th>Expired</th>
                    <th>Kode Akses</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    @push('scripts')
        <script>
            const kodeAksesRoute = "{{ route('kode-akses.index') }}";
        </script>
        <script src="{{ asset('libs/js/kode_akses.js') }}"></script>
    @endpush
</x-layout>
