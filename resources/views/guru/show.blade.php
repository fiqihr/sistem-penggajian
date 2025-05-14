<x-layout>
    <div class="mt-5 mb-4">
        <h3 class="">Detail Guru</h3>
        <p class="small font-italic">Guru &rsaquo; Detail guru</p>
    </div>
    <div class="container-fluid  bg-white rounded-lg p-4 shadow-sm mb-5">
        <div class="d-flex">
            <div class="col-md-4 d-flex justify-content-center align-items-center" style="padding: 1rem;">
                <img src="{{ asset('storage/images/' . $guru->photo) }}" alt="Profile" class="img-fluid rounded-lg shadow"
                    style=" width: 100%; object-fit: cover;">
            </div>

            <div class="col-md-8 pl-md-2">
                <h3 class="font-weight-bold">{{ $guru->user->name }}</h3>
                <h4 class="font-weight-bold text-primary">{{ $guru->nig }}</h4>
                <hr>
                <table class="table table-borderless table-striped small w-100">
                    <tr>
                        <td style="width: 30%">Email</td>
                        <td style="width: 10px">:</td>
                        <td class="font-weight-bold">{{ $guru->user->email }}</td>
                    </tr>
                    <tr>
                        <td>Nama Jabatan</td>
                        <td style="width: 10px">:</td>
                        <td class="font-weight-bold">{{ $guru->jabatan->nama_jabatan }}</td>
                    </tr>
                    <tr>
                        <td>Jenis Kelamin</td>
                        <td>:</td>
                        <td class="font-weight-bold">{{ $guru->jenis_kelamin }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Masuk</td>
                        <td>:</td>
                        <td class="font-weight-bold">{{ formatTanggal($guru->tanggal_masuk) }}</td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>:</td>
                        <td class="font-weight-bold">{{ $guru->status }}</td>
                    </tr>
                </table>
            </div>
        </div>

    </div>
</x-layout>
