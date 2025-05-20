<x-layout>
    <div class="mt-5 mb-4">
        <h3 class="">Edit Jabatan</h3>
        <p class="small font-italic">Jabatan &rsaquo; Edit Jabatan</p>
    </div>

    <div class="container-fluid  bg-white rounded-lg p-4 shadow-sm">
        <form action="{{ route('jabatan.update', $data->id_jabatan) }}" method="POST" class="col-lg-8 mx-auto">
            @method('PUT')
            @csrf
            <div class="mb-4">
                <label for="nama_jabatan" class="form-label">Nama Jabatan</label>
                <input id="nama_jabatan" type="text" name="nama_jabatan" id="nama_jabatan"
                    class="form-control @error('nama_jabatan') is-invalid @enderror" value="{{ $data->nama_jabatan }}"
                    required placeholder="Masukkan nama jabatan...">
                @error('nama_jabatan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label for="gaji_pokok" class="form-label">Gaji Pokok</label>
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text">Rp.</div>
                    </div>
                    <input id="gaji_pokok" type="number" name="gaji_pokok" id="gaji_pokok"
                        class="form-control @error('gaji_pokok') is-invalid @enderror" value="{{ $data->gaji_pokok }}"
                        required placeholder="Masukkan gaji pokok...">
                    @error('gaji_pokok')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <hr class="mb-4 mt-4">
            <div class="d-flex justify-content-end">
                <button type="submit" class=" btn btn-orange"><i class="fa-solid fa-pen-nib"></i></i>
                    <span class="ml-1">Update</span>
                </button>
            </div>
        </form>
    </div>
</x-layout>
