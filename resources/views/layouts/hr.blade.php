<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    {{-- Top Navbar --}}
    <header class="navbar navbar-dark sticky-top top-navbar flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 d-flex align-items-center gap-2" href="{{ route('hr.dashboard') }}">
            <i class="bi bi-grid-fill"></i> HR Dashboard
        </a>
        <div class="navbar-nav flex-row align-items-center px-3 gap-3">
            <span class="text-white-50 small d-none d-md-inline">
                <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
            </span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right me-1"></i>Logout
                </button>
            </form>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">
            {{-- Sidebar --}}
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="pt-4 px-2">
                    {{-- User Card --}}
                    <div class="text-center mb-4 px-3">
                        <div class="user-avatar mx-auto mb-2" style="width:48px;height:48px;font-size:1.1rem;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <div class="text-white fw-semibold small">{{ Auth::user()->name }}</div>
                        <span class="badge" style="background:rgba(255,255,255,0.15);color:rgba(255,255,255,0.7);font-size:0.7rem;">HR Admin</span>
                    </div>

                    <p class="text-uppercase px-3 mb-2" style="font-size:0.65rem;color:rgba(255,255,255,0.35);letter-spacing:1.5px;font-weight:600;">Menu Utama</p>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('hr.dashboard') ? 'active' : '' }}" href="{{ route('hr.dashboard') }}">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('hr.departments.*') ? 'active' : '' }}" href="{{ route('hr.departments.index') }}">
                                <i class="bi bi-building"></i> Departemen
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('hr.positions.*') ? 'active' : '' }}" href="{{ route('hr.positions.index') }}">
                                <i class="bi bi-briefcase"></i> Jabatan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('hr.schedules.*') ? 'active' : '' }}" href="{{ route('hr.schedules.index') }}">
                                <i class="bi bi-clock-history"></i> Jadwal Kerja
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('hr.employees.*') ? 'active' : '' }}" href="{{ route('hr.employees.index') }}">
                                <i class="bi bi-people"></i> Karyawan
                            </a>
                        </li>
                    </ul>

                    <p class="text-uppercase px-3 mt-3 mb-2" style="font-size:0.65rem;color:rgba(255,255,255,0.35);letter-spacing:1.5px;font-weight:600;">Transaksi</p>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('hr.attendances.*') ? 'active' : '' }}" href="{{ route('hr.attendances.index') }}">
                                <i class="bi bi-calendar-check"></i> Absensi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('hr.leave-requests.*') ? 'active' : '' }}" href="{{ route('hr.leave-requests.index') }}">
                                <i class="bi bi-envelope-paper"></i> Persetujuan Cuti
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('hr.salaries.*') ? 'active' : '' }}" href="{{ route('hr.salaries.index') }}">
                                <i class="bi bi-cash-stack"></i> Data Gaji
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            {{-- Main Content --}}
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                @yield('content')

                {{-- Footer --}}
                <footer class="app-footer mt-5">
                    <small>&copy; {{ date('Y') }} HR Dashboard &mdash; Built with Laravel 12 &amp; Bootstrap 5</small>
                </footer>
            </main>
        </div>
    </div>
</body>
</html>
