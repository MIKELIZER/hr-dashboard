<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark emp-navbar shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('employee.dashboard') }}">
                <i class="bi bi-person-workspace"></i> Employee Portal
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}" href="{{ route('employee.dashboard') }}">
                            <i class="bi bi-house-door"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('employee.attendances.*') ? 'active' : '' }}" href="{{ route('employee.attendances.index') }}">
                            <i class="bi bi-calendar-check"></i> Absensi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('employee.leave-requests.*') ? 'active' : '' }}" href="{{ route('employee.leave-requests.index') }}">
                            <i class="bi bi-envelope-paper"></i> Cuti
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('employee.salaries.*') ? 'active' : '' }}" href="{{ route('employee.salaries.index') }}">
                            <i class="bi bi-cash-stack"></i> Gaji
                        </a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="user-avatar">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <span class="text-white small fw-semibold d-none d-md-inline">{{ Auth::user()->name }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-outline-light btn-sm" type="submit">
                            <i class="bi bi-box-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        @yield('content')
    </main>

    <footer class="app-footer">
        <small>&copy; {{ date('Y') }} Employee Portal &mdash; HR Dashboard System</small>
    </footer>
</body>
</html>
