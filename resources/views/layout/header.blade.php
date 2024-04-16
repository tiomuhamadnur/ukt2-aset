<header class="header">
    <div class="logo-wrapper">
        <a href="{{ route('dashboard.index') }}" class="logo">
            <img src="{{ asset('assets/img/asetlogo2.png') }}" alt="" style="max-height: 200px" />
        </a>
    </div>
    <div class="header-items">
        <ul class="header-actions">
            {{-- <li class="dropdown mt-4">
                <a href="#" id="notifications" data-toggle="dropdown" aria-haspopup="true">
                    <i class="icon-bell"></i>
                    <span class="count-label">1</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right lrg" aria-labelledby="notifications">
                    <div class="dropdown-menu-header">
                        Notifications (1)
                    </div>
                    <ul class="header-notifications">
                        <li>
                            <a href="#">
                                <div class="user-img not-busy">
                                    <img src="https://img.freepik.com/free-psd/3d-illustration-human-avatar-profile_23-2150671142.jpg"
                                        alt="User" />
                                </div>
                                <div class="details">
                                    <div class="user-title">{{ auth()->user()->name }}</div>
                                    <div class="noti-details">Mencapai target kinerja harian.</div>
                                    <div class="noti-date">Oct 20, 07:30 pm</div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </li> --}}
            <li class="dropdown mt-4">
                <a href="#" id="userSettings" class="user-settings" data-toggle="dropdown" aria-haspopup="true">
                    <span class="text-white fw-bolder p-1">{{ auth()->user()->name }}</span>
                    <span class="avatar header-user">
                        <img src="{{ auth()->user()->photo != null ? asset('storage/' . auth()->user()->photo) : 'https://img.freepik.com/free-psd/3d-illustration-human-avatar-profile_23-2150671142.jpg' }}"
                            alt="Photo">
                        <span class="status not-busy"></span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userSettings">
                    <div class="header-profile-actions">
                        <div class="header-user-profile">
                            <div class="header-user">
                                <img src="{{ auth()->user()->photo != null ? asset('storage/' . auth()->user()->photo) : 'https://img.freepik.com/free-psd/3d-illustration-human-avatar-profile_23-2150671142.jpg' }}"
                                    alt="Photo">
                            </div>
                            <h5 class="text-center">{{ auth()->user()->name }}</h5>
                            <p>{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('user.profile') }}"><i class="icon-user1"></i> Profile Saya</a>
                        <a href="{{ route('user.profile.edit.password') }}"><i class="icon-lock"></i> Ubah Password</a>
                        <a href="javascript;"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            <i class="icon-log-out1"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</header>
