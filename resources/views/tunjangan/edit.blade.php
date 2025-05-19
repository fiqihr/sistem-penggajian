<x-layout>
    <div class="mt-5 mb-4">
        <h3 class="">Edit Potongan Gaji</h3>
        <p class="small font-italic">Potongan Gaji &rsaquo; Edit potongan Gaji</p>
    </div>
    <div class="container-fluid  bg-white rounded-lg p-4 shadow-sm">
        <form action="{{ route('tunjangan.update', $data->id_tunjangan) }}" method="POST" class="col-lg-6 mx-auto">
            @method('PUT')
            @csrf
            <div class="mb-4">
                <label for="id_guru" class="form-label">Nama Guru</label>
                <input id="id_guru" type="text" name="id_guru" id="id_guru"
                    class="form-control @error('id_guru') is-invalid @enderror" value="{{ $data->guru->user->name }}"
                    disabled>
            </div>
            <div class="mb-4">
                <label for="bulan" class="form-label">Nama Guru</label>
                <input id="bulan" type="text" name="bulan" id="bulan"
                    class="form-control @error('bulan') is-invalid @enderror" value="{{ formatBulan($data->bulan) }}"
                    disabled>
                @error('bulan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label for="jml_tunjangan" class="form-label">Jumlah Tunjangan</label>
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text">Rp.</div>
                    </div>
                    <input id="jml_tunjangan" type="number" name="jml_tunjangan" id="jml_tunjangan"
                        class="form-control @error('jml_tunjangan') is-invalid @enderror"
                        value="{{ $data->jml_tunjangan }}" required>
                    @error('jml_tunjangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <input type="hidden" name="jml_tunjangan_lama" value="{{ $data->jml_tunjangan }}">
            </div>
            <hr class="mb-4 mt-4">
            <div class="d-flex justify-content-end">
                <button type="submit" class=" btn btn-primary"><i class="fa-solid fa-pen-nib"></i>
                    <span class="ml-1">Update</span>
                </button>
            </div>
        </form>
    </div>
</x-layout>
