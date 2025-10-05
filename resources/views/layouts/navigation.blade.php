<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                        @if(file_exists(public_path('images/logo/logo.png')))
                            <img src="{{ asset('images/logo/logo.png') }}" alt="Logo" class="app-logo">
                        @elseif(file_exists(public_path('images/logo/logo.jpg')))
                            <img src="{{ asset('images/logo/logo.jpg') }}" alt="Logo" class="app-logo">
                        @elseif(file_exists(public_path('images/logo/logo.svg')))
                            <img src="{{ asset('images/logo/logo.svg') }}" alt="Logo" class="app-logo">
                        @elseif(file_exists(public_path('images/logo/logo.webp')))
                            <img src="{{ asset('images/logo/logo.webp') }}" alt="Logo" class="app-logo">
                        @else
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                        @endif
                        <span class="text-xl font-bold text-gray-800 hidden sm:block">Sistem PBL</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <i class="fas fa-tachometer-alt mr-1"></i>{{ __('Dashboard') }}
                    </x-nav-link>

                    @if(auth()->user()->isAdmin())
                        <!-- Admin Only - Periode Akademik (Gabungan: Mata Kuliah + Tahun Ajaran + Semester) -->
                        <x-nav-link :href="route('academic-periods.index')" :active="request()->routeIs('academic-periods.*') || request()->routeIs('projects.*')">
                            <i class="fas fa-calendar-alt mr-1"></i>{{ __('Periode Akademik') }}
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
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex items-center">
                                <span>{{ Auth::user()->name }}</span>
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                    @if(auth()->user()->isAdmin()) bg-red-100 text-red-800
                                    @elseif(auth()->user()->isKoordinator()) bg-purple-100 text-purple-800
                                    @elseif(auth()->user()->isDosen()) bg-blue-100 text-blue-800
                                    @else bg-green-100 text-green-800
                                    @endif">
                                    {{ ucfirst(Auth::user()->role) }}
                                </span>
                            </div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
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
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <i class="fas fa-tachometer-alt mr-1"></i>{{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if(auth()->user()->isAdmin())
                <!-- Admin Menu - Periode Akademik -->
                <x-responsive-nav-link :href="route('academic-periods.index')" :active="request()->routeIs('academic-periods.*') || request()->routeIs('projects.*')">
                    <i class="fas fa-calendar-alt mr-1"></i>{{ __('Periode Akademik') }}
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
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="flex items-center justify-between">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                        @if(auth()->user()->isAdmin()) bg-red-100 text-red-800
                        @elseif(auth()->user()->isKoordinator()) bg-purple-100 text-purple-800
                        @elseif(auth()->user()->isDosen()) bg-blue-100 text-blue-800
                        @else bg-green-100 text-green-800
                        @endif">
                        {{ ucfirst(Auth::user()->role) }}
                    </span>
                </div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
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
