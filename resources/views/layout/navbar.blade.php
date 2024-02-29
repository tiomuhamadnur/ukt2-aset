<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="dashboardsDropdown" role="button" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <i class="icon-devices_other nav-icon"></i>
        Dashboard
    </a>
    <ul class="dropdown-menu" aria-labelledby="dashboardsDropdown">
        <li>
            <a class="dropdown-item" href="{{ route('dashboard.index') }}">Dashboard</a>
        </li>
    </ul>
</li>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="appsDropdown" role="button" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <i class="icon-database nav-icon"></i>
        Kinerja
    </a>
    <ul class="dropdown-menu" aria-labelledby="dashboardsDropdown">
        <li>
            <a class="dropdown-item" href="{{ route('kinerja.saya') }}">Laporan Kerja Saya</a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('kinerja.index') }}">List Laporan Kerja</a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('formasi.index') }}">Formasi Personel</a>
        </li>
    </ul>
</li>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="appsDropdown" role="button" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-id-card nav-icon"></i>
        Absensi
    </a>
    <ul class="dropdown-menu" aria-labelledby="dashboardsDropdown">
        <li>
            <a class="dropdown-item" href="{{ route('absensi.index') }}">Data Absensi</a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('absensi.my_index') }}">Absensi Saya</a>
        </li>
    </ul>
</li>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="appsDropdown" role="button" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-calendar-times nav-icon"></i>
        Cuti
    </a>
    <ul class="dropdown-menu" aria-labelledby="dashboardsDropdown">
        <li>
            <a class="dropdown-item" href="{{ route('cuti.index') }}">Data Cuti/Izin</a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('cuti.saya') }}">Cuti Saya</a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('cuti.create') }}">Permohonan Cuti/Izin</a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('cuti.approval_page') }}">Halaman Approval</a>
        </li>
    </ul>
</li>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="appsDropdown" role="button" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <i class="icon-database nav-icon"></i>
        Masterdata
    </a>
    <ul class="dropdown-menu" aria-labelledby="dashboardsDropdown">
        <li>
            <a class="dropdown-item" href="{{ route('data_essentials.index') }}">Data Essentials</a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('data_relasi.index') }}">Data Relasi</a>
        </li>
    </ul>
</li>
