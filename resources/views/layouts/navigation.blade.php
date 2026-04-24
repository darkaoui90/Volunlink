<nav x-data="{ open: false }" class="bg-gradient-to-r from-[#C1272D] to-[#8B1A1F] shadow-lg">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                            <span class="text-[#C1272D] font-bold text-lg">V</span>
                        </div>
                        <div class="text-white">
                            <div class="font-bold text-lg">Volunlink</div>
                            <div class="text-xs text-red-100">World Cup 2030</div>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-red-100 hover:text-white hover:bg-red-700/50 focus:outline-none focus:text-white transition duration-150 ease-in-out {{ request()->routeIs('dashboard', 'admin.dashboard', 'coordinator.dashboard', 'supervisor.dashboard', 'volunteer.dashboard') ? 'bg-red-800/50 text-white' : '' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7-7M5 10v10a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 00-1-1H6a1 1 0 00-1 1v4a1 1 0 001 1h3a1 1 0 001-1z"></path>
                        </svg>
                        Dashboard
                    </a>

                    @if (auth()->user()->normalizedRole() === \App\Models\User::ROLE_ADMIN)
                        <a href="{{ route('admin.missions.index') }}" 
                           class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-red-100 hover:text-white hover:bg-red-700/50 focus:outline-none focus:text-white transition duration-150 ease-in-out {{ request()->routeIs('admin.missions.index', 'admin.missions.show', 'admin.missions.edit') ? 'bg-red-800/50 text-white' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2v2a2 2 0 012 2v7a2 2 0 01-2 2H7a2 2 0 01-2-2V9a2 2 0 012-2V7a2 2 0 00-2-2z"></path>
                            </svg>
                            Manage Missions
                        </a>

                        <a href="{{ route('admin.missions.create') }}" 
                           class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-red-100 hover:text-white hover:bg-red-700/50 focus:outline-none focus:text-white transition duration-150 ease-in-out {{ request()->routeIs('admin.missions.create') ? 'bg-red-800/50 text-white' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Create Mission
                        </a>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="relative">
                    <button @click="open = ! open" 
                            class="inline-flex items-center px-3 py-2 border border-red-800/50 text-sm font-medium rounded-md text-red-100 hover:text-white hover:bg-red-700/50 focus:outline-none focus:text-white transition duration-150 ease-in-out">
                        <div class="flex items-center space-x-2">
                            <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <span>{{ Auth::user()->name }}</span>
                        </div>
                        <svg class="fill-current h-4 w-4 ml-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 z-50 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                         @click.away="open = false">
                        <div class="py-1">
                            <a href="{{ route('profile.edit') }}" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span>Profile</span>
                                </div>
                            </a>

                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4 4m4-4H3m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        <span>Log Out</span>
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" 
                        class="inline-flex items-center justify-center p-2 rounded-md text-red-100 hover:text-white hover:bg-red-700/50 focus:outline-none focus:text-white transition duration-150 ease-in-out">
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
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}" 
               class="block px-3 py-2 rounded-md text-base font-medium text-red-100 hover:text-white hover:bg-red-700/50 focus:outline-none focus:text-white transition duration-150 ease-in-out {{ request()->routeIs('dashboard', 'admin.dashboard', 'coordinator.dashboard', 'supervisor.dashboard', 'volunteer.dashboard') ? 'bg-red-800/50 text-white' : '' }}">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7-7M5 10v10a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 00-1-1H6a1 1 0 00-1 1v4a1 1 0 001 1h3a1 1 0 001-1z"></path>
                    </svg>
                    <span>Dashboard</span>
                </div>
            </a>

            @if (auth()->user()->normalizedRole() === \App\Models\User::ROLE_ADMIN)
                <a href="{{ route('admin.missions.index') }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-red-100 hover:text-white hover:bg-red-700/50 focus:outline-none focus:text-white transition duration-150 ease-in-out {{ request()->routeIs('admin.missions.index', 'admin.missions.show', 'admin.missions.edit') ? 'bg-red-800/50 text-white' : '' }}">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2v2a2 2 0 012 2V7a2 2 0 01-2 2H7a2 2 0 01-2-2V9a2 2 0 012-2V7a2 2 0 00-2-2z"></path>
                        </svg>
                        <span>Manage Missions</span>
                    </div>
                </a>

                <a href="{{ route('admin.missions.create') }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-red-100 hover:text-white hover:bg-red-700/50 focus:outline-none focus:text-white transition duration-150 ease-in-out {{ request()->routeIs('admin.missions.create') ? 'bg-red-800/50 text-white' : '' }}">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Create Mission</span>
                    </div>
                </a>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-red-700/50">
            <div class="px-3 py-2">
                <div class="text-base font-medium text-white">{{ Auth::user()->name }}</div>
                <div class="text-sm text-red-100">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}" 
                   class="block px-3 py-2 rounded-md text-base font-medium text-red-100 hover:text-white hover:bg-red-700/50 focus:outline-none focus:text-white transition duration-150 ease-in-out">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>Profile</span>
                    </div>
                </a>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-red-100 hover:text-white hover:bg-red-700/50 focus:outline-none focus:text-white transition duration-150 ease-in-out">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4 4m4-4H3m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span>Log Out</span>
                        </div>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
