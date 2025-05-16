<x-layout>
    @php
        $user = Auth::user();
        $akses = $user->hak_akses;
    @endphp
    @if ($akses === 'admin')
        <div class="container-fluid text-gray-700">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                    class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> --}}
            </div>
            <h5 class="mb-5 ">Halo Selamat Datang, {{ $user->name }} 🖐️</h5>
            <div class="row">

                <!-- Earnings (Monthly) Card Example -->
                <div class="col-xl-6 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Guru</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jumlahGuru }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa-solid fa-people-group fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Earnings (Monthly) Card Example -->
                <div class="col-xl-6 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Jabatan</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jumlahJabatan }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa-solid fa-users-rectangle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Earnings (Monthly) Card Example -->
                {{-- <div class="col-xl-3 col-md-6 mb-4">
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
                </div> --}}

                <!-- Pending Requests Card Example -->
                {{-- <div class="col-xl-3 col-md-6 mb-4">
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
                </div> --}}

            </div>
            <div class="row">

                <div class="col-4">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 ">
                            <h6 class="m-0 font-weight-bold text-primary">Status Guru</h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="chart-pie pt-4 pb-2">
                                <canvas id="myPieChart"></canvas>
                            </div>
                            <div class="mt-4 text-center small">
                                <span class="mr-2">
                                    <i class="fas fa-circle text-success"></i> Guru Tidak Tetap
                                </span>
                                <span class="mr-2">
                                    <i class="fas fa-circle text-primary"></i> Guru Tetap
                                </span>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Data Guru Berdasarkan Gender</h6>
                        </div>
                        <div class="card-body">
                            <h4 class="small font-weight-bold">
                                Laki-laki ({{ $jumlahLaki }} orang)
                                <span class="float-right">{{ $persenLaki }}%</span>
                            </h4>
                            <div class="progress mb-4">
                                <div class="progress-bar bg-warning" role="progressbar"
                                    style="width: {{ $persenLaki }}%" aria-valuenow="{{ $persenLaki }}"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <h4 class="small font-weight-bold">Perempuan ({{ $jumlahPerempuan }} orang) <span
                                    class="float-right">{{ $persenPerempuan }}%</span></h4>
                            <div class="progress mb-4">
                                <div class="progress-bar bg-danger" role="progressbar"
                                    style="width: {{ $persenPerempuan }}%" aria-valuenow="{{ $persenPerempuan }}"
                                    aria-valuemin="0" aria-valuemax="100"></div>
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
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
    <script src="{{ asset('libs/bootstrap/vendor/chart.js/Chart.min.js') }}"></script>
    <script>
        const ctx = document.getElementById("myPieChart");
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                // kosongkan label agar tidak muncul sebagai label chart
                labels: [],
                datasets: [{
                    data: [{{ $guruTetap }}, {{ $guruTidakTetap }}],
                    backgroundColor: ['#3B82F6', '#10B981'],
                    hoverBackgroundColor: ['#2563EB', '#059669'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, data) {
                            // Ambil data value
                            const value = data.datasets[0].data[tooltipItem.index];
                            // Buat label manual berdasarkan index
                            const customLabels = ['Guru Tetap', 'Guru Tidak Tetap'];
                            return `${customLabels[tooltipItem.index]}: ${value}`;
                        }
                    }
                },
                legend: {
                    display: false
                },
                cutoutPercentage: 60,
            }
        });
    </script>

</x-layout>
