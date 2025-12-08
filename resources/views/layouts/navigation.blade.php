<nav x-data="{ open: false }" class="modern-navbar">
    <!-- Primary Navigation Menu -->
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
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
                        <div class="hidden lg:block border-l-2 border-white/30 pl-3 ml-1">
                            <div class="logo-text-main">Sistem Informasi PBL</div>
                            <div class="logo-text-sub">Politeknik Negeri Tanah Laut</div>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-2 sm:ms-6 lg:flex items-center">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard', 'admin.dashboard', 'koordinator.dashboard', 'dosen.dashboard', 'mahasiswa.dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if(auth()->user()->isAdmin())
                        <!-- Admin Only - Periode Akademik (Gabungan: Mata Kuliah + Tahun Ajaran + Semester) -->
                        <x-nav-link :href="route('academic-periods.index')" :active="request()->routeIs('academic-periods.*') || request()->routeIs('projects.*')">
                            {{ __('Periode Akademik') }}
                        </x-nav-link>
                        
                        <!-- Admin Only - User Management -->
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                            {{ __('Kelola User') }}
                        </x-nav-link>
                    @endif

                    @if(auth()->user()->isKoordinator() || auth()->user()->isAdmin())
                        <!-- Koordinator, Admin Menu - Kelola Kelompok -->
                        <x-nav-link :href="route('classrooms.index')" :active="request()->routeIs('classrooms.*')">
                            {{ __('Kelas') }}
                        </x-nav-link>
                        <x-nav-link :href="route('groups.index')" :active="request()->routeIs('groups.*')">
                            {{ __('Kelompok') }}
                        </x-nav-link>
                    @endif

                    @if(auth()->user()->isAdmin())
                        <!-- Admin Only - Criteria -->
                        <x-nav-link :href="route('criteria.index')" :active="request()->routeIs('criteria.*')">
                            {{ __('Kriteria') }}
                        </x-nav-link>
                        <!-- Admin Only - Google Drive Settings -->
                        <x-nav-link :href="route('settings.google-drive.index')" :active="request()->routeIs('settings.google-drive.*')">
                            {{ __('Google Drive') }}
                        </x-nav-link>
                    @endif

                    @if(auth()->user()->isDosen() || auth()->user()->isKoordinator() || auth()->user()->isAdmin())
                        <!-- Dosen, Koordinator, Admin - Kelola Target Mingguan -->
                        <x-nav-link :href="route('targets.index')" :active="request()->routeIs('targets.*')">
                            {{ __('Target Mingguan') }}
                        </x-nav-link>
                    @endif

                    @if(auth()->user()->isDosen() || auth()->user()->isKoordinator() || auth()->user()->isAdmin())
                        <!-- Dropdown Menu Penilaian -->
                        <x-dropdown align="top" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-white hover:text-white/80 focus:outline-none transition ease-in-out duration-150 {{ request()->routeIs('scores.*') || request()->routeIs('student-scores.*') || request()->routeIs('target-reviews.*') ? 'border-b-2 border-white' : '' }}">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                    </svg>
                                    Penilaian
                                    <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                @if(auth()->user()->isDosen() || auth()->user()->isAdmin())
                                    <x-dropdown-link :href="route('scores.student-input')">
                                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        {{ __('Input Nilai') }}
                                    </x-dropdown-link>
                                @endif
                                
                                @if(auth()->user()->isDosen() || auth()->user()->isKoordinator() || auth()->user()->isAdmin())
                                    <x-dropdown-link :href="route('target-reviews.index')">
                                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ __('Review Target') }}
                                    </x-dropdown-link>
                                @endif
                                
                                <x-dropdown-link :href="route('scores.index')">
                                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    {{ __('Ranking Kelompok') }}
                                </x-dropdown-link>
                                
                                <x-dropdown-link :href="route('student-scores.index')">
                                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    {{ __('Ranking Mahasiswa') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    @endif

                    @if(auth()->user()->isMahasiswa())
                        <!-- Mahasiswa - Target Mingguan -->
                        <x-nav-link :href="route('targets.submissions.index')" :active="request()->routeIs('targets.submissions.*')">
                            {{ __('Target Mingguan') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden lg:flex lg:items-center lg:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="user-profile-btn group">
                            <div class="flex items-center space-x-2.5">
                                <div class="relative w-10 h-10 rounded-full overflow-hidden border-2 border-white/30 group-hover:border-white/60 transition-all duration-200 shadow-md">
                                    <img src="{{ Auth::user()->profile_photo_url }}" 
                                         alt="{{ Auth::user()->name }}" 
                                         class="w-full h-full object-cover">
                                </div>
                                <div class="hidden xl:block text-left">
                                    <div class="user-name">{{ Auth::user()->name }}</div>
                                    <div class="user-role">{{ ucfirst(Auth::user()->role) }}</div>
                                </div>
                            </div>
                            <svg class="ml-2 h-4 w-4 text-white opacity-80 group-hover:opacity-100 transition-all" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
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
            <div class="-me-2 flex items-center lg:hidden">
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
    <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:hidden mobile-menu">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard', 'admin.dashboard', 'koordinator.dashboard', 'dosen.dashboard', 'mahasiswa.dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if(auth()->user()->isAdmin())
                <!-- Admin Menu - Periode Akademik -->
                <x-responsive-nav-link :href="route('academic-periods.index')" :active="request()->routeIs('academic-periods.*') || request()->routeIs('projects.*')">
                    {{ __('Periode Akademik') }}
                </x-responsive-nav-link>
                
                <!-- Admin Menu - User Management -->
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    {{ __('Kelola User') }}
                </x-responsive-nav-link>
            @endif

            @if(auth()->user()->isKoordinator() || auth()->user()->isAdmin())
                <!-- Koordinator, Admin Menu -->
                <x-responsive-nav-link :href="route('classrooms.index')" :active="request()->routeIs('classrooms.*')">
                    {{ __('Kelas') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('groups.index')" :active="request()->routeIs('groups.*')">
                    {{ __('Kelompok') }}
                </x-responsive-nav-link>
            @endif

            @if(auth()->user()->isAdmin())
                <!-- Admin Only - Criteria -->
                <x-responsive-nav-link :href="route('criteria.index')" :active="request()->routeIs('criteria.*')">
                    {{ __('Kriteria') }}
                </x-responsive-nav-link>
                <!-- Admin Only - Google Drive Settings -->
                <x-responsive-nav-link :href="route('settings.google-drive.index')" :active="request()->routeIs('settings.google-drive.*')">
                    {{ __('Google Drive') }}
                </x-responsive-nav-link>
            @endif

            @if(auth()->user()->isDosen() || auth()->user()->isKoordinator() || auth()->user()->isAdmin())
                <!-- Dosen, Koordinator, Admin - Target Mingguan -->
                <x-responsive-nav-link :href="route('targets.index')" :active="request()->routeIs('targets.*')">
                    {{ __('Target Mingguan') }}
                </x-responsive-nav-link>
                
                <!-- Menu Penilaian (Grouped) -->
                <div class="pl-4 border-l-2 border-gray-200/30 ml-4 space-y-1">
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 py-2">
                        📋 Penilaian
                    </div>
                    
                    @if(auth()->user()->isDosen() || auth()->user()->isAdmin())
                        <x-responsive-nav-link :href="route('scores.student-input')" :active="request()->routeIs('scores.student-input*')">
                            {{ __('Input Nilai') }}
                        </x-responsive-nav-link>
                    @endif
                    
                    @if(auth()->user()->isDosen() || auth()->user()->isKoordinator() || auth()->user()->isAdmin())
                        <x-responsive-nav-link :href="route('target-reviews.index')" :active="request()->routeIs('target-reviews.*')">
                            {{ __('Review Target') }}
                        </x-responsive-nav-link>
                    @endif
                    
                    <x-responsive-nav-link :href="route('scores.index')" :active="request()->routeIs('scores.index') || request()->routeIs('scores.show') || request()->routeIs('scores.create') || request()->routeIs('scores.edit')">
                        {{ __('Ranking Kelompok') }}
                    </x-responsive-nav-link>
                    
                    <x-responsive-nav-link :href="route('student-scores.index')" :active="request()->routeIs('student-scores.*')">
                        {{ __('Ranking Mahasiswa') }}
                    </x-responsive-nav-link>
                </div>
            @endif

            @if(auth()->user()->isMahasiswa())
                <!-- Mahasiswa Menu - Dashboard Only -->
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200/30">
            <div class="px-4">
                <div class="flex items-center space-x-3 mb-2">
                    <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-primary-200 shadow-sm">
                        <img src="{{ Auth::user()->profile_photo_url }}" 
                             alt="{{ Auth::user()->name }}" 
                             class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1">
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-xs text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium role-badge-{{ auth()->user()->role }}">
                    <i class="fa-solid fa-circle text-xs mr-1"></i>
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
