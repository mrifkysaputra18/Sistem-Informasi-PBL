<nav x-data="{ open: false }" class="modern-navbar">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-24">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-4 group">
                        @if(file_exists(public_path('images/logo/logo.png')))
                            <img src="{{ asset('images/logo/logo.png') }}" alt="Logo" class="app-logo">
                        @elseif(file_exists(public_path('images/logo/logo.jpg')))
                            <img src="{{ asset('images/logo/logo.jpg') }}" alt="Logo" class="app-logo">
                        @elseif(file_exists(public_path('images/logo/logo.svg')))
                            <img src="{{ asset('images/logo/logo.svg') }}" alt="Logo" class="app-logo">
                        @elseif(file_exists(public_path('images/logo/logo.webp')))
                            <img src="{{ asset('images/logo/logo.webp') }}" alt="Logo" class="app-logo">
                        @else
                            <x-application-logo class="block h-10 w-auto fill-current text-gray-800" />
                        @endif
                        <div class="hidden lg:block border-l-2 border-gray-200 pl-4">
                            <div class="logo-text-main">Sistem Informasi PBL</div>
                            <div class="logo-text-sub">Politeknik Negeri Tanah Laut</div>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <i class="fas fa-tachometer-alt mr-1"></i>{{ __('Dashboard') }}
                    </x-nav-link>

                    @if(auth()->user()->isAdmin())
                        <!-- Admin Only - Periode Akademik (Gabungan: Mata Kuliah + Tahun Ajaran + Semester) -->
                        <x-nav-link :href="route('academic-periods.index')" :active="request()->routeIs('academic-periods.*') || request()->routeIs('projects.*')">
                            <i class="fas fa-calendar-alt mr-1"></i>{{ __('Periode Akademik') }}
                        </x-nav-link>
                        
                        <!-- Admin Only - User Management -->
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                            <i class="fas fa-users-cog mr-1"></i>{{ __('Kelola User') }}
                        </x-nav-link>
                    @endif

                    @if(auth()->user()->isKoordinator() || auth()->user()->isAdmin())
                        <!-- Koordinator, Admin Menu - Kelola Kelompok -->
                        <x-nav-link :href="route('classrooms.index')" :active="request()->routeIs('classrooms.*')">
                            <i class="fas fa-school mr-1"></i>{{ __('Kelas') }}
                        </x-nav-link>
                        <x-nav-link :href="route('groups.index')" :active="request()->routeIs('groups.*')">
                            <i class="fas fa-users mr-1"></i>{{ __('Kelompok') }}
                        </x-nav-link>
                    @endif

                    @if(auth()->user()->isAdmin())
                        <!-- Admin Only - Criteria -->
                        <x-nav-link :href="route('criteria.index')" :active="request()->routeIs('criteria.*')">
                            <i class="fas fa-list-check mr-1"></i>{{ __('Kriteria') }}
                        </x-nav-link>
                    @endif

                    @if(auth()->user()->isDosen() || auth()->user()->isKoordinator())
                        <!-- Dosen, Koordinator - Review Target -->
                        <x-nav-link :href="route('target-reviews.index')" :active="request()->routeIs('target-reviews.*')">
                            <i class="fas fa-clipboard-check mr-1"></i>{{ __('Review Target') }}
                        </x-nav-link>
                    @endif

                    @if(auth()->user()->isDosen() || auth()->user()->isKoordinator() || auth()->user()->isAdmin())
                        <!-- Dosen, Koordinator, Admin - Scores (NO MAHASISWA!) -->
                        <x-nav-link :href="route('scores.index')" :active="request()->routeIs('scores.*')">
                            <i class="fas fa-trophy mr-1"></i>{{ __('Nilai & Ranking') }}
                        </x-nav-link>
                    @endif

                    @if(auth()->user()->isMahasiswa())
                        <!-- Mahasiswa Menu - Dashboard Only -->
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="user-profile-btn group">
                            <div class="flex items-center space-x-3">
                                <div class="user-avatar">
                                    <span class="user-avatar-text">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span>
                                </div>
                                <div class="hidden md:block text-left">
                                    <div class="user-name">{{ Auth::user()->name }}</div>
                                    <div class="user-role">{{ ucfirst(Auth::user()->role) }}</div>
                                </div>
                            </div>
                            <svg class="ml-2 h-5 w-5 text-gray-400 group-hover:text-gray-600 transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="hamburger-btn">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden mobile-menu">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <i class="fas fa-tachometer-alt mr-1"></i>{{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if(auth()->user()->isAdmin())
                <!-- Admin Menu - Periode Akademik -->
                <x-responsive-nav-link :href="route('academic-periods.index')" :active="request()->routeIs('academic-periods.*') || request()->routeIs('projects.*')">
                    <i class="fas fa-calendar-alt mr-1"></i>{{ __('Periode Akademik') }}
                </x-responsive-nav-link>
                
                <!-- Admin Menu - User Management -->
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    <i class="fas fa-users-cog mr-1"></i>{{ __('Kelola User') }}
                </x-responsive-nav-link>
            @endif

            @if(auth()->user()->isKoordinator() || auth()->user()->isAdmin())
                <!-- Koordinator, Admin Menu -->
                <x-responsive-nav-link :href="route('classrooms.index')" :active="request()->routeIs('classrooms.*')">
                    <i class="fas fa-school mr-1"></i>{{ __('Kelas') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('groups.index')" :active="request()->routeIs('groups.*')">
                    <i class="fas fa-users mr-1"></i>{{ __('Kelompok') }}
                </x-responsive-nav-link>
            @endif

            @if(auth()->user()->isAdmin())
                <!-- Admin Only - Criteria -->
                <x-responsive-nav-link :href="route('criteria.index')" :active="request()->routeIs('criteria.*')">
                    <i class="fas fa-list-check mr-1"></i>{{ __('Kriteria') }}
                </x-responsive-nav-link>
            @endif

            @if(auth()->user()->isDosen() || auth()->user()->isKoordinator() || auth()->user()->isAdmin())
                <!-- Dosen, Koordinator, Admin - Scores (NO MAHASISWA!) -->
                <x-responsive-nav-link :href="route('scores.index')" :active="request()->routeIs('scores.*')">
                    <i class="fas fa-trophy mr-1"></i>{{ __('Nilai & Ranking') }}
                </x-responsive-nav-link>
            @endif

            @if(auth()->user()->isMahasiswa())
                <!-- Mahasiswa Menu - Dashboard Only -->
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200/30">
            <div class="px-4">
                <div class="flex items-center space-x-3 mb-2">
                    <div class="user-avatar user-avatar-sm">
                        <span class="user-avatar-text text-xs">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span>
                    </div>
                    <div class="flex-1">
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-xs text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium role-badge-{{ auth()->user()->role }}">
                    <i class="fas fa-circle text-xs mr-1"></i>
                    {{ ucfirst(Auth::user()->role) }}
                </span>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
