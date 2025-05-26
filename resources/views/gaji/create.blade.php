<x-layout title="Cetak Slip Gaji">
    <div class="mt-5 mb-4">
        <h3 class="">Cetak Slip Gaji</h3>
        <p class="small font-italic">Data Gaji &rsaquo; Cetak Slip Gaji</p>
    </div>
    <div class="container-fluid  bg-white rounded-lg p-4 shadow-sm">
        <form action="{{ route('gaji.detail') }}" class="col-lg-6 mx-auto">
            <div class="mb-4">
                <label for="id_guru">Nama Guru</label>
                <select class="form-control" name="id_guru" id="id_guru">
                    <option selected disabled value="">-- Nama Guru --</option>
                    @foreach ($semua_guru as $guru)
                        <option value="{{ $guru->id_guru }}">{{ $guru->user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="bulan" class="form-label">Bulan</label>
                <input type="month" id="bulan" name="bulan" class="form-control">
                <div id="bulan-error" class="text-danger mt-1" style="display: none;"></div>
            </div>
            <div class="mb-4">
                <label for="id_tunjangan">Tunjangan <span class="small font-italic">(Kosongi jika tidak ada
                        tunjangan)</span> </label>
                <select class="form-control" name="id_tunjangan" id="id_tunjangan">
                    <option selected disabled value="">-- Tunjangan --</option>
                    @foreach ($semua_tunjangan as $tunjangan)
                        <option value="{{ $tunjangan->id_tunjangan }}">{{ $tunjangan->nama_tunjangan }}</option>
                    @endforeach
                </select>
            </div>
            <hr class="mb-4 mt-4">
            <div class="d-flex justify-content-end">
                <button type="submit" id="btn-submit" class=" btn btn-primary"><i class="fa-solid fa-floppy-disk"></i>
                    <span class="ml-1">Simpan</span>
                </button>
            </div>
        </form>
    </div>
    @push('scripts')
        @if (session('gagal'))
            <script>
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    text: "{{ session('gagal') }}",
                    showConfirmButton: true,
                    confirmButtonColor: "#3B82F6",
                });
            </script>
        @endif
        <script>
            const presensiCek = "{{ route('presensi.cek') }}";
        </script>
        <script src="{{ asset('libs/js/gaji.js') }}"></script>
    @endpush


</x-layout>
