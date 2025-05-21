<x-layout title="Edit Guru">
    <div class="mt-5 mb-4">
        <h3 class="">Edit Guru</h3>
        <p class="small font-italic">Data Guru &rsaquo; Edit guru</p>
    </div>
    <div class="container-fluid bg-white rounded-lg p-4 shadow-sm mb-5">
        <div class="row">
            <!-- Gambar -->
            <div class="col-4 d-flex flex-column align-items-center justify-content-center ">
                <div class="d-flex flex-column align-items-center justify-content-center">

                    @if ($guru->photo == 'default.svg')
                        <img src="{{ asset('libs/img/default.svg') }}" alt="Profile" class="rounded-lg shadow mx-auto"
                            style="width: auto; height: auto%; max-width: 300px; object-fit: cover;">
                    @else
                        <img src="{{ asset('storage/images/' . $guru->photo) }}" alt="Profile"
                            class="rounded-lg shadow mx-auto"
                            style="width: auto; height: auto%; max-width: 300px; object-fit: cover;">
                    @endif

                    {{-- <img src="{{ asset('storage/images/' . $guru->photo) }}" alt="Profile"
                        class="rounded-lg shadow mx-auto"
                        style="width: auto; height: auto%; max-width: 300px; object-fit: cover;"> --}}


                    <div class="text-center mx-auto mt-2">
                        <btn class="btn btn-secondary" data-toggle="modal" data-target="#exampleModal">
                            <i class="fa-solid fa-camera-rotate"></i> <span>Update Foto</span>
                        </btn>
                    </div>
                </div>
            </div>
            <!-- Form -->
            <div class="col-md-8">
                <form action="{{ route('guru.update', $guru->id_guru) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="form-label">Nama Guru</label>
                        <input id="name" type="text" name="name"
                            class="form-control @error('name') is-invalid @enderror" required
                            placeholder="Masukkan nama guru..." value="{{ $guru->user->name }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="nig" class="form-label">NIG</label>
                        <input id="nig" type="number" name="nig"
                            class="form-control @error('nig') is-invalid @enderror" required
                            placeholder="Masukkan NIG..." value="{{ $guru->nig }}">
                        @error('nig')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-row mb-2">
                        <div class="form-group col-md-6">
                            <label for="jenis_kelamin">Jenis Kelamin</label>
                            <select class="form-control" name="jenis_kelamin" id="jenis_kelamin">
                                <option disabled value="">-- Pilih Jenis Kelamin --</option>
                                <option {{ $guru->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }} value="Laki-laki">
                                    Laki-laki</option>
                                <option {{ $guru->jenis_kelamin == 'Perempuan' ? 'selected' : '' }} value="Perempuan">
                                    Perempuan</option>
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="status">Status Guru</label>
                            <input id="status" type="text" name="status"
                                class="form-control @error('status') is-invalid @enderror" required
                                placeholder="Masukkan status guru..." value="{{ $guru->status }}">
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row mb-2">
                        <div class="form-group col-md-6">
                            <label for="id_jabatan">Jabatan</label>
                            <select class="form-control" name="id_jabatan" id="id_jabatan">
                                <option disabled value="">-- Pilih Jabatan --</option>
                                @foreach ($jabatan as $item)
                                    <option value="{{ $item->id_jabatan }}"
                                        {{ $item->id_jabatan == $guru->id_jabatan ? 'selected' : '' }}>
                                        {{ $item->nama_jabatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="tanggal_masuk">Tanggal Masuk</label>
                            <input id="tanggal_masuk" type="date" name="tanggal_masuk"
                                class="form-control @error('tanggal_masuk') is-invalid @enderror"
                                value="{{ $guru->tanggal_masuk }}" required>
                            @error('tanggal_masuk')
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
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Foto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="file" class="form-control" id="formFile" name="photo"
                        accept="image/png, image/jpeg, image/jpg">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i
                            class="fa-solid fa-xmark"></i> <span class="ml-1">Batal</span> </button>
                    <button type="button" id="upload" class="btn btn-success"><i
                            class="fa-solid fa-cloud-arrow-up"></i>
                        <span class="ml-1">Upload</span> </button>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            document.querySelector('.modal-footer #upload').addEventListener('click', function() {
                const fileInput = document.querySelector('#formFile');
                const file = fileInput.files[0];
                const formData = new FormData();
                formData.append('photo', file);

                fetch(`{{ route('guru.uploadPhoto', ['id' => $guru->id_guru]) }}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.photo) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Foto berhasil diperbarui!',
                                timer: 2000,
                                showConfirmButton: false
                            });

                            // Ganti src dari elemen <img> agar langsung tampil foto baru
                            const img = document.querySelector('img[alt="Profile"]');
                            img.src =
                                `/storage/images/${data.photo}?t=${new Date().getTime()}`; // tambahkan timestamp agar tidak cache

                            // Tutup modal
                            $('#exampleModal').modal('hide');
                        } else {
                            alert('Gagal memperbarui foto.');
                        }
                    })
                    .catch(error => {
                        console.error('Upload error:', error);
                        alert('Terjadi kesalahan.');
                    });
            });
        </script>
    @endpush
</x-layout>
