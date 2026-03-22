<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .nav-link {
            padding: 6px 14px;
            border-radius: 6px;
            transition: all .15s ease;
        }

        .nav-link:hover {
            background: rgba(255,255,255,.12);
        }

        .active-nav {
            background: rgba(255,255,255,.22);
            font-weight: 500;
            border-radius: 6px;
        }

        .navbar-user-block {
            line-height: 1.1;
            padding: 6px 12px;
            border-radius: 6px;
        }

        .navbar-user-block small {
            font-size: .75rem;
            opacity: .85;
        }

        .navbar .btn {
            height: 32px;
            display: flex;
            align-items: center;
        }

        .notification-wrapper {
            position: relative;
            cursor: pointer;
            display: inline-block;
        }

        .notification-badge {
            position: absolute;
            top: 2px;
            right: 4px;
            width: 8px;
            height: 8px;
            background: red;
            border-radius: 50%;
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

        .notification-wrapper {
            transition: opacity 0.3s ease, transform 0.2s ease;
        }

        .notification-wrapper.bell-hidden {
            display: none !important;
        }

        /* Prevent layout shift */
        .notification-wrapper {
            min-width: 44px;
            text-align: center;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100 bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-semibold">UoG Annual Magazine</a>

            <button class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                @php
                    $homeRoute = route('dashboard');

                    if(auth()->user()->hasRole('Admin')){
                        $homeRoute = route('admin.dashboard');
                    }
                    elseif(auth()->user()->hasRole('Marketing Manager')){
                        $homeRoute = route('manager.dashboard');
                    }
                    elseif(auth()->user()->hasRole('Marketing Coordinator')){
                        $homeRoute = route('coordinator.dashboard');
                    }
                    elseif(auth()->user()->hasRole('Student')){
                        $homeRoute = route('student.dashboard');
                    }

                    $lastLogin = session('last_login_at');
                    $isUnread = !auth()->user()->notification_read_at;
                @endphp

                <ul class="navbar-nav ms-auto align-items-center gap-3">
                    <!-- HOME -->
                    <li class="nav-item">
                        <a href="{{ $homeRoute }}"
                           class="nav-link text-white
                           {{ request()->routeIs('admin.dashboard','manager.dashboard','coordinator.dashboard','student.dashboard') ? 'active-nav' : '' }}">
                            Home
                        </a>
                    </li>

                    <!-- ADMIN DROPDOWN -->
                    @if(auth()->user()->hasRole('Admin'))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white
                               {{ request()->routeIs('admin.users.*','admin.faculties.*','admin.contributions.*','admin.academic-years.*','admin.reports','admin.settings') ? 'active-nav' : '' }}"
                               href="#"
                               role="button"
                               data-bs-toggle="dropdown">
                                Manage Entities
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                                       href="{{ route('admin.users.index') }}">
                                        Manage Users
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('admin.faculties.*') ? 'active' : '' }}"
                                       href="{{ route('admin.faculties.index') }}">
                                        Manage Faculties
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('admin.contributions.*') ? 'active' : '' }}"
                                       href="{{ route('admin.contributions.index') }}">
                                        Manage Contributions
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('admin.academic-years.*') ? 'active' : '' }}"
                                       href="{{ route('admin.academic-years.index') }}">
                                        Manage Academic Years
                                    </a>
                                </li>

                                <li><hr class="dropdown-divider"></li>

                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('admin.reports') ? 'active' : '' }}"
                                       href="{{ route('admin.reports') }}">
                                        Reports
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}"
                                       href="{{ route('admin.settings') }}">
                                        System Settings
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    <!-- USER -->
                    <li class="nav-item">
                        <a href="{{ route('profile.show') }}" class="text-decoration-none text-white">
                            <div class="d-flex align-items-center gap-2 navbar-user-block
                                 {{ request()->routeIs('profile.show') ? 'active-nav' : '' }}">
                                @if(auth()->user()->profile_photo)
                                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
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

                    <!-- LOGOUT -->
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                            @csrf
                            <button class="btn btn-outline-light btn-sm">Logout</button>
                        </form>
                    </li>

                    <!-- 🔔 BELL -->
                    <li class="nav-item notification-wrapper"
                        id="notificationBell"
                        data-bs-toggle="tooltip"
                        title="Important Notification! Click to Read."
                        style="display: none;">
                        <span class="nav-link text-white">
                            <span id="bellIcon" class="{{ $isUnread ? 'bell-animate' : '' }}">🔔</span>
                        </span>

                        @if($isUnread)
                            <span class="notification-badge" id="notificationBadge"></span>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- MODAL -->
    <div class="modal fade" id="notificationModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>⚠️ Important Notice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    This demo system uses <strong>stateless ephemeral cloud storage</strong>.<br><br>
                    Previously uploaded files (user profile photos, contribution documents and associated images) may become unavailable after some time.<br><br>
                    Please re-upload if missing.
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary" id="markNotificationRead">Understood</button>
                </div>
            </div>
        </div>
    </div>

    <main class="flex-fill">
        {{ $slot }}
    </main>

    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <small>© {{ date('Y') }} University of Greenwich</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const bell = document.getElementById('notificationBell');
            const bellIcon = document.getElementById('bellIcon');
            const modalElement = document.getElementById('notificationModal');
            const badge = document.getElementById('notificationBadge');

            if (!bell) return;

            function shouldShowBell() {
                const bellDismissed = localStorage.getItem('bell_dismissed');
                const dismissalTime = localStorage.getItem('bell_dismissal_time');

                if (bellDismissed === 'true' && dismissalTime) {
                    const timeSinceDismissal = Date.now() - parseInt(dismissalTime);
                    if (timeSinceDismissal < 90000) return false;

                    localStorage.removeItem('bell_dismissed');
                    localStorage.removeItem('bell_dismissal_time');
                }
                return true;
            }

            function showBell() {
                bell.style.display = 'inline-block';
                new bootstrap.Tooltip(bell);
            }

            function hideBell() {
                bell.style.display = 'none';
            }

            function initBellVisibility() {
                const sessionDismissed = sessionStorage.getItem('bell_session_dismissed');
                if (sessionDismissed === 'true') {
                    hideBell();
                    return;
                }

                shouldShowBell() ? showBell() : hideBell();
            }

            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                if (el.style.display !== 'none') {
                    new bootstrap.Tooltip(el);
                }
            });

            let modal = modalElement ? new bootstrap.Modal(modalElement) : null;

            bell.addEventListener('click', function () {
                if (bell.style.display !== 'none' && modal) {
                    modal.show();
                }
            });

            document.getElementById('markNotificationRead')?.addEventListener('click', function () {
                fetch("{{ route('notification.read') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json"
                    }
                });

                bellIcon?.classList.remove('bell-animate');
                badge?.remove();
                modal?.hide();

                sessionStorage.setItem('bell_session_dismissed', 'true');
                localStorage.setItem('bell_dismissed', 'true');
                localStorage.setItem('bell_dismissal_time', Date.now());

                hideBell();
            });

            document.getElementById('logoutForm')?.addEventListener('submit', function() {
                localStorage.removeItem('bell_dismissed');
                localStorage.removeItem('bell_dismissal_time');
                sessionStorage.removeItem('bell_session_dismissed');
            });

            initBellVisibility();
        });
    </script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

</body>

</html>