<x-layout>
    <div class="mt-5 mb-4">
        <h3 class="">Profile</h3>
        <p class="small font-italic">Profile &rsaquo; Edit Profile</p>
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

                    <div class="text-center mx-auto mt-2">
                        <btn class="btn btn-secondary" data-toggle="modal" data-target="#exampleModal">
                            <i class="fa-solid fa-camera-rotate"></i> <span>Update Foto</span>
                        </btn>
                    </div>
                </div>
            </div>
            <!-- Form -->
            <div class="col-md-8">
                <form action="{{ route('profile.update', $guru->id_guru) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="form-label">Nama</label>
                        <input id="name" type="text" name="name"
                            class="form-control @error('name') is-invalid @enderror" value="{{ $guru->user->name }}"
                            required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" type="email" name="email"
                            class="form-control @error('name') is-invalid @enderror" value="{{ $guru->user->email }}"
                            required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="jenis_kelamin">Jenis Kelamin</label>
                        <select class="form-control" name="jenis_kelamin" id="jenis_kelamin">
                            <option disabled value="">-- Pilih Jenis Kelamin --</option>
                            <option {{ $guru->jenis_kelamin == 'laki-laki' ? 'selected' : '' }} value="laki-laki">
                                Laki-laki</option>
                            <option {{ $guru->jenis_kelamin == 'perempuan' ? 'selected' : '' }} value="perempuan">
                                Perempuan</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="ml-2 btn btn-orange"><i class="fa-solid fa-pen-nib"></i>
                            <span class="ml-1">Update Profil</span>
                        </button>
                    </div>
                </form>
                <hr class="mb-4 mt-4">
                <!-- Form Update Password -->
                <form action="{{ route('profile.updatePassword') }}" method="POST" class="mt-4">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Password Saat Ini</label>
                        <input type="password" name="current_password"
                            class="form-control @error('current_password') is-invalid @enderror" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Password Baru</label>
                        <input type="password" name="new_password"
                            class="form-control @error('new_password') is-invalid @enderror" required>
                        @error('new_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" class="form-control" required>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-warning text-white"><i
                                class="fa-solid fa-unlock-keyhole"></i> Update
                            Password</button>
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
            const guruMessage = {!! json_encode(session('berhasil')) !!};

            if (guruMessage) {
                guruBerhasil(guruMessage);
            }

            function guruBerhasil(message) {
                Swal.fire({
                    position: "center",
                    icon: "success",
                    text: message,
                    showConfirmButton: false,
                    timer: 1500,
                });
            }
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

                            const img = document.querySelector('img[alt="Profile"]');
                            img.src =
                                `/storage/images/${data.photo}?t=${new Date().getTime()}`;

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
