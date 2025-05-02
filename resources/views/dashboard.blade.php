<x-layout>
    @php
        $user = Auth::user();
        $akses = $user->hak_akses;
    @endphp
    @if ($akses === 'admin')
        <div class="d-flex justify-content-center bg-gradient-light rounded-lg p-5">
            ini dashboard admin
        </div>
    @elseif ($akses === 'guru')
        <div class="container-fluid  rounded-lg p-2 overflow-hidden">
            <h3 class="mb-5">Halo Selamat Datang, {{ $user->name }} 🖐️</h3>
            <div class="row bg-white py-5 rounded-lg overflow-hidden" style="height: 400px;">
                <div class="col-lg-4 rounded-lg d-flex justify-content-center align-items-center" style="height: 100%;">
                    <img src="{{ asset('storage/images/' . $user->guru->photo) }}" alt="Profile" class="img-fluid shadow"
                        style="max-height: 100%; object-fit: contain;">
                </div>
                <div class="col-lg-8  d-flex align-items-center" style="height: 100%;">
                    <div>
                        <h2>{{ $user->name }}</h2>
                        <h4 class="text-primary font-weight-bold">{{ $user->guru->nig }}</h4>
                        <hr class="my-4">
                        <table>
                            <tr>
                                <td style="width: 200px">Jabatan</td>
                                <td style="width: 10px">:</td>
                                <td class="font-weight-bold">{{ $user->guru->jabatan->nama_jabatan }}</td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>:</td>
                                <td class="font-weight-bold">{{ $user->guru->status }}</td>
                            </tr>
                            <tr>
                                <td>Jenis Kelamin</td>
                                <td>:</td>
                                <td class="font-weight-bold">{{ $user->guru->jenis_kelamin }}</td>
                            </tr>
                            <tr>
                                <td>Tanggal Masuk</td>
                                <td>:</td>
                                <td class="font-weight-bold">{{ formatTanggal($user->guru->tanggal_masuk) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    @endif

</x-layout>
