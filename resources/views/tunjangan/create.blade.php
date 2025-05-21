<x-layout title="Tambah Tunjangan Gaji">
    <div class="mt-5 mb-4" title="Tambah Tunjangan Gaji">
        <h3 class="">Tambah Tunjangan Gaji</h3>
        <p class="small font-italic">Data Tunjangan Gaji &rsaquo; Tambah Tunjangan Gaji</p>
    </div>
    <div class="container-fluid  bg-white rounded-lg p-4 shadow-sm">
        <form action="{{ route('tunjangan.store') }}" method="POST" class="col-lg-6 mx-auto">
            @csrf
            <div class="mb-4">
                <label for="nama_tunjangan" class="form-label">Nama Tunjangan</label>
                <input id="nama_tunjangan" type="text" name="nama_tunjangan" id="nama_tunjangan"
                    class="form-control @error('nama_tunjangan') is-invalid @enderror" required>
            </div>
            <div class="mb-4">
                <label for="jml_tunjangan" class="form-label">Jumlah Tunjangan</label>
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text">Rp.</div>
                    </div>
                    <input id="jml_tunjangan" type="number" name="jml_tunjangan" id="jml_tunjangan"
                        class="form-control @error('jml_tunjangan') is-invalid @enderror" required>
                    @error('jml_tunjangan')
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
