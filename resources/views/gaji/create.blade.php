<x-layout>
    <div class="mt-5 mb-4">
        <h3 class="">Cetak Slip Gaji</h3>
        <p class="small font-italic">Potongan Gaji &rsaquo; Cetak Slip Gaji</p>
    </div>
    <div class="container-fluid  bg-white rounded-lg p-4 shadow-sm">
        <form action="{{ route('gaji.store') }}" method="POST" class="col-lg-6 mx-auto">
            @csrf
            <div class="mb-4">
                <label for="id_guru">Nama Guru</label>
                <select class="form-control" name="id_guru" id="id_guru">
                    <option selected disabled value="">-- Nama Guru --</option>
                    @foreach ($semua_guru as $guru)
                        <option value="{{ $guru->id_guru }}">{{ $guru->user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="bulan" class="form-label">Bulan</label></label>
                <input id="bulan" type="month" name="bulan" id="bulan"
                    class="form-control @error('bulan') is-invalid @enderror" value="{{ old('bulan') }}" required
                    placeholder="Masukkan bulan...">
                @error('bulan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <hr class="mb-4 mt-4">
            <div class="d-flex justify-content-end">
                <button type="submit" class=" btn btn-primary"><i class="fa-solid fa-floppy-disk"></i>
                    <span class="ml-1">Simpan</span>
                </button>
            </div>
        </form>
    </div>
</x-layout>
