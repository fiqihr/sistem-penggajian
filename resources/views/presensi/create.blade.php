<x-layout title="Isi Presensi">
    <div class="mt-5 mb-4">
        <h3 class="">Isi Presensi</h3>
        <p class="small font-italic">Data Presensi &rsaquo; Isi Presensi</p>
    </div>
    <div class="container-fluid  bg-white rounded-lg p-4 shadow-sm">
        <form action="{{ route('presensi.store') }}" method="POST" class="col-lg-8 mx-auto">
            @csrf
            <div class="form-row mb-2">
                <div class="form-group col-md-6 pl-md-2">
                    <label for="bulan">Bulan, Tahun</label>
                    <input id="bulan" type="month" name="bulan" id="bulan"
                        class="form-control @error('bulan') is-invalid @enderror" value="{{ old('bulan') }}" required
                        placeholder="Masukkan bulan guru...">
                    @error('bulan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6 pl-md-2">
                    <label for="id_guru">Nama Guru</label>
                    <select class="form-control" name="id_guru" id="id_guru">
                        <option selected disabled value="">-- Pilih Guru --</option>
                        @foreach ($guru as $item)
                            <option value="{{ $item->id_guru }}">{{ $item->user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row mb-2">
                <div class="form-group col-md-4 pl-md-2">
                    <label for="hadir">Jumlah Hadir</label>
                    <input id="hadir" type="number" name="hadir" id="hadir"
                        class="form-control @error('hadir') is-invalid @enderror" value="{{ old('hadir') }}" required>
                    @error('hadir')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-4 pl-md-2">
                    <label for="sakit">Jumlah Sakit</label>
                    <input id="sakit" type="number" name="sakit" id="sakit"
                        class="form-control @error('sakit') is-invalid @enderror" value="{{ old('sakit') }}" required>
                    @error('sakit')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-4 pl-md-2">
                    <label for="tidak_hadir">Jumlah Tidak Hadir</label>
                    <input id="tidak_hadir" type="number" name="tidak_hadir" id="tidak_hadir"
                        class="form-control @error('tidak_hadir') is-invalid @enderror" value="{{ old('tidak_hadir') }}"
                        required>
                    @error('tidak_hadir')
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
