<x-layout title="Edit Presensi">
    <div class="mt-5 mb-4">
        <h3 class="">Edit Presensi</h3>
        <p class="small font-italic">Data Presensi &rsaquo; Edit Presensi</p>
    </div>
    <div class="container-fluid  bg-white rounded-lg p-4 shadow-sm">
        <form action="{{ route('presensi.update', $data->id_presensi) }}" method="POST" class="col-lg-8 mx-auto">
            @method('PUT')
            @csrf
            <div class="form-row mb-2">
                <div class="form-group col-md-6 pl-md-2">
                    <label for="bulan">Bulan, Tahun</label>
                    <input id="bulan" type="month" name="bulan" id="bulan" class="form-control" required
                        value="{{ $data->bulan }}">
                    @error('bulan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6 pl-md-2">
                    <label for="id_guru">Nama Guru</label>
                    <select class="form-control" name="id_guru" id="id_guru">
                        <option selected disabled value="">-- Pilih Guru --</option>
                        @foreach ($guru as $item)
                            <option value="{{ $item->id_guru }}"
                                {{ $item->id_guru == $data->id_guru ? 'selected' : '' }}>
                                {{ $item->user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row mb-2">
                <div class="form-group col-md-4 pl-md-2">
                    <label for="hadir">Jumlah Hadir</label>
                    <input id="hadir" type="number" name="hadir" id="hadir" class="form-control" required
                        value="{{ $data->hadir }}">
                    @error('hadir')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-4 pl-md-2">
                    <label for="sakit">Jumlah Sakit</label>
                    <input id="sakit" type="number" name="sakit" id="sakit" class="form-control" required
                        value="{{ $data->sakit }}">
                    @error('sakit')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-4 pl-md-2">
                    <label for="tidak_hadir">Jumlah Tidak Hadir</label>
                    <input id="tidak_hadir" type="number" name="tidak_hadir" id="tidak_hadir" class="form-control"
                        required value="{{ $data->tidak_hadir }}">
                    @error('tidak_hadir')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <hr class="mb-4 mt-4">
            <div class="d-flex justify-content-end">
                <button type="submit" class=" btn btn-orange"><i class="fa-solid fa-pen-nib"></i>
                    <span class="ml-1">Update</span>
                </button>
            </div>
        </form>
    </div>
</x-layout>
