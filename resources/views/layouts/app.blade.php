<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    @php use Illuminate\Support\Str; @endphp

    <style>
        .nav-link { padding: 6px 14px; border-radius: 6px; transition: all .15s ease; }
        .nav-link:hover { background: rgba(255,255,255,.12); }
        .active-nav { background: rgba(255,255,255,.22); font-weight: 500; border-radius: 6px; }

        .navbar-user-block { line-height: 1.1; padding: 6px 12px; border-radius: 6px; }
        .navbar-user-block small { font-size: .75rem; opacity: .85; }

        .navbar .btn { height: 32px; display: flex; align-items: center; }

        .notification-wrapper { position: relative; cursor: pointer; display: inline-block; }
        .notification-badge {
            position: absolute; top: 2px; right: 4px;
            width: 8px; height: 8px;
            background: red; border-radius: 50%;
        }

        @keyframes bell-ring {
            0% { transform: rotate(0); }
            15% { transform: rotate(15deg); }
            30% { transform: rotate(-10deg); }
            45% { transform: rotate(8deg); }
            60% { transform: rotate(-6deg); }
            75% { transform: rotate(4deg); }
            100% { transform: rotate(0); }
        }

        .bell-animate {
            display: inline-block;
            animation: bell-ring 1s ease-in-out infinite;
            transform-origin: top center;
        }

        .notification-wrapper { min-width: 44px; text-align: center; }
    </style>
</head>

<body class="d-flex flex-column min-vh-100 bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-semibold">UoG Annual Magazine</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">

            @php
                $homeRoute = route('dashboard');

                if(auth()->user()->hasRole('Admin')) $homeRoute = route('admin.dashboard');
                elseif(auth()->user()->hasRole('Marketing Manager')) $homeRoute = route('manager.dashboard');
                elseif(auth()->user()->hasRole('Marketing Coordinator')) $homeRoute = route('coordinator.dashboard');
                elseif(auth()->user()->hasRole('Student')) $homeRoute = route('student.dashboard');

                $lastLogin = session('last_login_at');
                $isUnread = !auth()->user()->notification_read_at;

                $photo = auth()->user()->profile_photo ?? null;
                $photoUrl = $photo && Str::startsWith($photo, ['http://','https://'])
                    ? $photo
                    : ($photo ? asset('storage/'.$photo) : null);
            @endphp

            <ul class="navbar-nav ms-auto align-items-center gap-3">

                <li class="nav-item">
                    <a href="{{ $homeRoute }}" class="nav-link text-white {{ request()->routeIs('*dashboard') ? 'active-nav' : '' }}">
                        Home
                    </a>
                </li>

                @if(auth()->user()->hasRole('Admin'))
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                        Manage Entities
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">Manage Users</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.faculties.index') }}">Manage Faculties</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.contributions.index') }}">Manage Contributions</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.academic-years.index') }}">Manage Academic Years</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('admin.reports') }}">Reports</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.settings') }}">System Settings</a></li>
                    </ul>
                </li>
                @endif

                <!-- USER -->
                <li class="nav-item">
                    <a href="{{ route('profile.show') }}" class="text-decoration-none text-white">
                        <div class="d-flex align-items-center gap-2 navbar-user-block">

                            @if($photoUrl)
                                <img src="{{ $photoUrl }}"
                                     class="rounded-circle border"
                                     width="40"
                                     height="40"
                                     style="object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center"
                                     style="width:40px; height:40px;">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                            @endif

                            <div>
                                <div class="fw-semibold small">{{ auth()->user()->name }}</div>
                                <small>
                                    {{ $lastLogin ? 'Last login: '.\Carbon\Carbon::parse($lastLogin)->format('d M Y H:i') : 'Welcome 👋' }}
                                </small>
                            </div>
                        </div>
                    </a>
                </li>

                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-outline-light btn-sm">Logout</button>
                    </form>
                </li>

                <!-- 🔔 -->
                <li class="nav-item notification-wrapper" id="notificationBell" style="display:none;">
                    <span class="nav-link text-white">
                        <span class="{{ $isUnread ? 'bell-animate' : '' }}">🔔</span>
                    </span>
                    @if($isUnread)
                        <span class="notification-badge"></span>
                    @endif
                </li>

            </ul>
        </div>
    </div>
</nav>

<main class="flex-fill">
    {{ $slot }}
</main>

<footer class="bg-dark text-white text-center py-3 mt-auto">
    <small>© {{ date('Y') }} University of Greenwich</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
