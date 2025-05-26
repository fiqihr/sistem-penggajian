<x-layout title="Gaji Saya">
    <div class="mt-5 mb-4">
        <h3 class="">Gaji Saya</h3>
        <p class="small font-italic">Data Gaji Saya</p>
    </div>
    <div class="container-fluid bg-white rounded-lg p-4 shadow-sm mb-5">
        <table id="my-table" class="table table-bordered table-striped small w-100">
            <thead id="mytable-thead">
                <tr>
                    <th class="text-center">No</th>
                    <th>Bulan</th>
                    <th>Total Gaji</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    @push('scripts')
        <script>
            // Variabel ini akan tetap di sini agar bisa diakses oleh gaji_saya.js
            const gajiSayaRoute = "{{ route('gaji-saya.index') }}";
            const gajiSayaMessage = {!! json_encode(session('berhasil')) !!};
        </script>
        <script src="{{ asset('libs/js/gaji_saya.js') }}"></script>
    @endpush
</x-layout>
