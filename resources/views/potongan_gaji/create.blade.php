<x-layout title="Tambah Potongan Gaji">
    <div class="mt-5 mb-4">
        <h3 class="">Tambah Potongan Gaji</h3>
        <p class="small font-italic">Data Potongan Gaji &rsaquo; Tambah Potongan Gaji</p>
    </div>
    <div class="container-fluid  bg-white rounded-lg p-4 shadow-sm">
        <form action="{{ route('potongan-gaji.store') }}" method="POST" class="col-lg-6 mx-auto">
            @csrf
            <div class="mb-4">
                <label for="nama_potongan" class="form-label">Nama Potongan</label>
                <input id="nama_potongan" type="text" name="nama_potongan" id="nama_potongan"
                    class="form-control @error('nama_potongan') is-invalid @enderror" value="{{ old('nama_potongan') }}"
                    required placeholder="Masukkan nama potongan...">
                @error('nama_potongan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label for="jml_potongan" class="form-label">Jumlah Potongan</label>
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text">Rp.</div>
                    </div>
                    <input id="jml_potongan" type="number" name="jml_potongan" id="jml_potongan"
                        class="form-control @error('jml_potongan') is-invalid @enderror"
                        value="{{ old('jml_potongan') }}" required placeholder="Masukkan jumlah potongan...">
                    @error('jml_potongan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
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
