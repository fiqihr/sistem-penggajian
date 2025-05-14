<x-layout>
    @php
        $user = Auth::user();
        $akses = $user->hak_akses;
    @endphp
    @if ($akses === 'admin')
        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                        class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> --}}
            </div>
            <div class="row">

                <!-- Earnings (Monthly) Card Example -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Earnings (Monthly)</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">$40,000</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Earnings (Monthly) Card Example -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Earnings (Annual)</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">$215,000</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Earnings (Monthly) Card Example -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tasks
                                    </div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-auto">
                                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">50%</div>
                                        </div>
                                        <div class="col">
                                            <div class="progress progress-sm mr-2">
                                                <div class="progress-bar bg-info" role="progressbar" style="width: 50%"
                                                    aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Requests Card Example -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Pending Requests</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-comments fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($akses === 'guru')
        <div class="container-fluid  rounded-lg p-2 overflow-hidden">
            <h3 class="mb-5">Halo Selamat Datang, {{ $user->name }} 🖐️</h3>
            {{-- <p>{{ $cekGajiMasuk }}</p> --}}
            @foreach ($cekGajiMasuk as $item)
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Halo!</strong> Slip gaji anda pada bulan {{ formatBulan($item->bulan) }} sudah dikirim.
                    <span>Silahkan cek <a href="{{ route('gaji-saya.index') }}">disini.</a></span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endforeach
            <div class="row bg-white py-5 rounded-lg overflow-hidden" style="height: 400px;">
                <div class="col-lg-4 rounded-lg d-flex justify-content-center align-items-center" style="height: 100%;">
                    <img src="{{ asset('storage/images/' . $user->guru->photo) }}" alt="Profile"
                        class="img-fluid shadow" style="max-height: 100%; object-fit: contain;">
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
