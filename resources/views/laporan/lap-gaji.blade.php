<x-layout>
    <div class="mt-5 mb-4">
        <h3 class="">Cetak Laporan Gaji</h3>
        <p class="small font-italic">Laporan &rsaquo; Cetak Laporan Gaji</p>
    </div>
    <div class="container-fluid  bg-white rounded-lg p-4 shadow-sm">
        <form action="{{ route('gaji.laporan.cetak') }}" method="POST" class="col-lg-6 mx-auto" target="_blank">
            @csrf
            <div class="mb-4">
                <label for="bulan" class="form-label">Bulan - Tahun</label></label>
                <input id="bulan" type="month" name="bulan" id="bulan"
                    class="form-control @error('bulan') is-invalid @enderror" value="{{ old('bulan') }}" required
                    placeholder="Masukkan bulan...">
                @error('bulan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <hr class="mb-4 mt-4">
            <div class="d-flex justify-content-end">
                <button type="submit" class=" btn btn-success"><i class="fa-solid fa-floppy-disk"></i>
                    <span class="ml-1">Cetak Laporan Gaji</span>
                </button>
            </div>
        </form>
    </div>
</x-layout>
