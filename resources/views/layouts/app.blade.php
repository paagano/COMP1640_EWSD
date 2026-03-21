<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Navbar Styling -->
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

    </style>
</head>


<body class="d-flex flex-column min-vh-100 bg-light">


<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">

    <div class="container">

        <!-- BRAND -->
        <a class="navbar-brand fw-semibold">
            UoG Annual Magazine
        </a>


        <!-- MOBILE TOGGLER -->
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
                            <a class="dropdown-item"
                               href="{{ route('admin.users.index') }}">
                                Manage Users
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item"
                               href="{{ route('admin.academic-years.index') }}">
                                Manage Academic Years
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item"
                               href="{{ route('admin.contributions.index') }}">
                                Manage Contributions
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item"
                               href="{{ route('admin.faculties.index') }}">
                                Manage Faculties
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <li>
                            <a class="dropdown-item"
                               href="{{ route('admin.reports') }}">
                                System Reports
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item"
                               href="{{ route('admin.settings') }}">
                                System Settings
                            </a>
                        </li>

                    </ul>

                </li>

                @endif



                <!-- USER PROFILE -->
                <li class="nav-item">

                    <a href="{{ route('profile.show') }}"
                       class="text-decoration-none text-white">

                        <div class="d-flex align-items-center gap-2 navbar-user-block
                             {{ request()->routeIs('profile.show') ? 'active-nav' : '' }}">

                            {{-- PROFILE IMAGE --}}
                            @if(auth()->user()->profile_photo)
                                <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                                     class="rounded-circle border img-fluid"
                                     width="40"
                                     height="40"
                                     style="object-fit: cover;" 
                                     alt="User Profile Photo">
                                     
                            @else
                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center"
                                     style="width:40px; height:40px; font-size:14px;">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                            @endif

                            {{-- USER INFO --}}
                            <div class="text-start">

                                <div class="fw-semibold small">

                                    {{ auth()->user()->name }}

                                    <span class="text-light opacity-75">
                                        ({{ auth()->user()->getRoleNames()->first() ?? 'User' }})
                                    </span>

                                </div>

                                <small class="text-light">

                                    @if($lastLogin)

                                        Last login: {{ \Carbon\Carbon::parse($lastLogin)->format('d M Y, H:i') }}

                                    @else

                                        🕒First login. Welcome!👋 

                                    @endif

                                </small>

                            </div>

                        </div>

                    </a>

                </li>



                <!-- LOGOUT -->
                <li class="nav-item">

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button class="btn btn-outline-light btn-sm">
                            Logout
                        </button>

                    </form>

                </li>

            </ul>

        </div>

    </div>

</nav>



<!-- PAGE CONTENT -->
<main class="flex-fill">

    {{ $slot }}

</main>



<!-- FOOTER -->
<footer class="bg-dark text-white text-center py-3 mt-auto">

    <small>
        © {{ date('Y') }} University of Greenwich
    </small>

</footer>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>