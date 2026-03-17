<nav x-data="{ adminMenu: false }" class="bg-dark border-b border-gray-100">
    <div class="container mx-auto px-4">
        <div class="flex justify-between h-16 items-center">

            {{-- ===================== --}}
            {{-- LEFT SIDE --}}
            {{-- ===================== --}}
            <div class="flex items-center space-x-6">

                <div class="text-white font-semibold text-lg">
                    UoG Magazine
                </div>

                {{-- HOME BUTTON (Role Aware) --}}
                <a href="
                    @if(auth()->user()->hasRole('Admin'))
                        {{ route('admin.dashboard') }}
                    @elseif(auth()->user()->hasRole('Student'))
                        {{ route('student.dashboard') }}
                    @elseif(auth()->user()->hasRole('Marketing Coordinator'))
                        {{ route('coordinator.dashboard') }}
                    @elseif(auth()->user()->hasRole('Marketing Manager'))
                        {{ route('manager.dashboard') }}
                    @else
                        {{ route('dashboard') }}
                    @endif
                "
                   class="text-gray-300 hover:text-white transition">
                    Home
                </a>

                {{-- ===================== --}}
                {{-- ADMIN DROPDOWN --}}
                {{-- ===================== --}}
                @if(auth()->check() && auth()->user()->hasRole('Admin'))
                    <div class="relative">

                        <button @click="adminMenu = !adminMenu"
                                class="text-gray-300 hover:text-white transition flex items-center space-x-1 focus:outline-none">
                            <span>Manage Entity</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                 stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        {{-- Dropdown --}}
                        <div x-show="adminMenu"
                             @click.away="adminMenu = false"
                             x-transition
                             class="absolute mt-2 w-56 bg-white rounded shadow-lg z-50">

                            <a href="{{ route('admin.users.index') }}"
                               class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                Manage Users
                            </a>

                            <a href="{{ route('admin.academic-years.index') }}"
                               class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                Manage Academic Years
                            </a>

                            <a href="{{ route('admin.contributions.index') }}"
                               class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                Manage Contributions
                            </a>

                            <a href="{{ route('admin.faculties.index') }}"
                               class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                Manage Faculties
                            </a>

                            <a href="{{ route('admin.reports') }}"
                               class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                System Reports
                            </a>

                            <a href="{{ route('admin.settings') }}"
                               class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                System Settings
                            </a>

                        </div>
                    </div>
                @endif

            </div>

            {{-- ===================== --}}
            {{-- RIGHT SIDE --}}
            {{-- ===================== --}}
            <div class="flex items-center space-x-4 text-white">

                <div class="text-right">
                    <div class="font-semibold">
                        {{ auth()->user()->name }}
                        <span class="text-sm text-gray-400">
                            ({{ auth()->user()->roles->first()?->name ?? 'User' }})
                        </span>
                    </div>

                    <div class="text-xs text-gray-400">
                        Last Login:
                        {{ auth()->user()->last_login_at
                            ? auth()->user()->last_login_at->format('d M Y, h:i A')
                            : 'First Login'
                        }}
                    </div>
                </div>

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="px-3 py-1 bg-gray-700 rounded hover:bg-gray-600">
                        Logout
                    </button>
                </form>

            </div>

        </div>
    </div>
</nav>