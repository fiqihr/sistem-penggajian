<ul class="navbar-nav bg-gradient-success sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('libs/img/logo-smk.png') }}" alt="logo" class="w-100">
        </div>
        <div class="sidebar-brand-text mx-3">Sistem Penggajian</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="/">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    @if (Auth::user()->hak_akses == 'guru')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('gaji-saya.index') }}">
                <i class="fa-solid fa-coins"></i>
                <span>Gaji Saya</span></a>
        </li>
    @elseif (Auth::user()->hak_akses == 'admin')
        <!-- Nav Item - Tables -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('jabatan.index') }}">
                <i class="fa-solid fa-briefcase"></i>
                <span>Jabatan</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('guru.index') }}">
                <i class="fa-solid fa-user-tie"></i>
                <span>Guru</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('presensi.index') }}">
                <i class="fa-solid fa-list-check"></i>
                <span>Presensi</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('potongan-gaji.index') }}">
                <i class="fa-solid fa-folder-minus"></i>
                <span>Potongan Gaji</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('tunjangan.index') }}">
                <i class="fa-solid fa-comment-dollar"></i>
                <span>Tunjangan</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('gaji.index') }}">
                <i class="fa-solid fa-coins"></i>
                <span>Gaji</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                aria-expanded="true" aria-controls="collapseTwo">
                <i class="fa-solid fa-copy"></i>
                <span>Laporan</span>
            </a>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="{{ route('gaji.laporan') }}">Laporan Gaji</a>
                    <a class="collapse-item" href="{{ route('presensi.laporan') }}">Laporan Presensi</a>
                </div>
            </div>
        </li>
    @endif


    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

    <!-- Sidebar Message -->


</ul>
