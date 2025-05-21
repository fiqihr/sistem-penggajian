<x-layout title="Tambah Guru">
    <div class="mt-5 mb-4">
        <h3 class="">Tambah Guru</h3>
        <p class="small font-italic">Data Guru &rsaquo; Tambah guru</p>
    </div>
    <div class="container-fluid  bg-white rounded-lg p-4 shadow-sm  mb-5">
        <form action="{{ route('guru.store') }}" method="POST" class="col-lg-8 mx-auto">
            @csrf
            <div class="mb-4">
                <label for="name" class="form-label">Nama Guru</label>
                <input id="name" type="text" name="name" id="name"
                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required
                    placeholder="Masukkan nama guru...">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label for="nig" class="form-label">NIG</label>
                <input id="nig" type="number" name="nig" id="nig"
                    class="form-control @error('nig') is-invalid @enderror" value="{{ old('nig') }}" required
                    placeholder="Masukkan NIG...">
                @error('nig')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-row mb-2">
                <div class="form-group col-md-6 pl-md-2">
                    <label for="jenis_kelamin">Jenis Kelamin</label>
                    <select class="form-control" name="jenis_kelamin" id="jenis_kelamin">
                        <option selected disabled value="">-- Pilih Jenis Kelamin --</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
                <div class="form-group col-md-6 pl-md-2">
                    <label for="status">Status Guru</label>
                    <select class="form-control" name="status" id="status">
                        <option selected disabled value="">-- Status Guru --</option>
                        <option value="Guru Tetap">Guru Tetap</option>
                        <option value="Guru Tidak Tetap">Guru Tidak Tetap</option>
                    </select>
                </div>
            </div>
            <div class="form-row mb-2">
                <div class="form-group col-md-6 pl-md-2">
                    <label for="id_jabatan">Jabatan</label>
                    <select class="form-control" name="id_jabatan" id="id_jabatan">
                        <option selected disabled value="">-- Pilih Jabatan --</option>
                        @foreach ($jabatan as $item)
                            <option value="{{ $item->id_jabatan }}">{{ $item->nama_jabatan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-6 pl-md-2">
                    <label for="tanggal_masuk">Tanggal Masuk</label>
                    <input id="tanggal_masuk" type="date" name="tanggal_masuk" id="tanggal_masuk"
                        class="form-control @error('tanggal_masuk') is-invalid @enderror"
                        value="{{ old('tanggal_masuk') }}" required placeholder="Masukkan gaji pokok...">
                    @error('tanggal_masuk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row mb-2">
                <div class="form-group col-md-6 pl-md-2">
                    <label for="email">Email Akun</label>
                    <input id="email" type="email" name="email" id="email"
                        class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required
                        placeholder="Masukkan email akun guru...">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6 pl-md-2">
                    <label for="password">Password Akun</label>
                    <input id="password" type="password" name="password" id="password"
                        class="form-control @error('password') is-invalid @enderror" value="{{ old('password') }}"
                        required placeholder="Buat password akun guru...">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <hr class="mb-4 mt-4">
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i>
                    <span class="ml-1">Simpan</span>
                </button>
            </div>
        </form>
    </div>
</x-layout>
