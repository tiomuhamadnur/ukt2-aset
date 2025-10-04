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
        <i class="fa fa-file nav-icon"></i>
        Kontrak
    </a>
    <ul class="dropdown-menu" aria-labelledby="dashboardsDropdown">
        @foreach ($seksiList as $item)
            <li>
                <a class="dropdown-item" href="{{ route('admin-kontrak.index', $item->uuid) }}">
                    {{ $item->name }}
                </a>
            </li>
        @endforeach
    </ul>
</li>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="appsDropdown" role="button" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-building nav-icon"></i>
        Gudang Utama
    </a>
    <ul class="dropdown-menu" aria-labelledby="dashboardsDropdown">
        @foreach ($seksiList as $item)
            <li>
                <a class="dropdown-item" href="{{ route('admin.gudang-utama', $item->uuid) }}">
                    {{ $item->name }}
                </a>
            </li>
        @endforeach
    </ul>
</li>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="appsDropdown" role="button" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-truck nav-icon"></i>
        Daftar Pengiriman
    </a>
    <ul class="dropdown-menu" aria-labelledby="dashboardsDropdown">
        @foreach ($seksiList as $item)
            <li>
                <a class="dropdown-item" href="{{ route('admin.pengiriman.index', $item->uuid) }}">
                    {{ $item->name }}
                </a>
            </li>
        @endforeach
    </ul>
</li>