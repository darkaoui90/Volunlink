<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - Volunlink</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700|outfit:600,700,800|plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        [x-cloak] { display: none !important; }
        .sidebar-scrollbar::-webkit-scrollbar { width: 6px; }
        .sidebar-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
        .sidebar-scrollbar::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 3px; }
        .sidebar-scrollbar::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
        .auth-brand-word {
            font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
            letter-spacing: -0.05em;
        }
        .auth-brand-mark {
            position: relative;
            width: 52px;
            height: 38px;
            flex-shrink: 0;
        }
        .auth-brand-mark span {
            position: absolute;
            display: block;
            border-radius: 9999px;
        }
        .auth-brand-mark .auth-gold-left {
            left: 5px;
            top: 1px;
            width: 12px;
            height: 34px;
            transform: rotate(-31deg);
            background: linear-gradient(180deg, #ffbf4b 0%, #ff9120 100%);
        }
        .auth-brand-mark .auth-coral-right {
            left: 22px;
            top: 3px;
            width: 13px;
            height: 32px;
            transform: rotate(30deg);
            background: linear-gradient(180deg, #ff7f5b 0%, #d84d3f 100%);
        }
        .auth-brand-mark .auth-teal-base {
            left: 12px;
            top: 16px;
            width: 28px;
            height: 10px;
            border-radius: 9999px 9999px 14px 14px;
            background: linear-gradient(90deg, #47a7d8 0%, #53c89f 100%);
        }
        .auth-brand-mark .auth-sky-dot {
            right: 3px;
            top: 1px;
            width: 10px;
            height: 10px;
            background: #5ca8e5;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    @php
        $adminUser = auth()->user();
        $notificationsEnabled = \Illuminate\Support\Facades\Schema::hasTable('notifications');
        $recentNotifications = $notificationsEnabled
            ? $adminUser->notifications()->latest()->take(6)->get()
            : collect();
        $unreadNotificationsCount = $notificationsEnabled
            ? $adminUser->unreadNotifications()->count()
            : 0;
        $hasRecentNotifications = $recentNotifications->isNotEmpty();
    @endphp

    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="hidden lg:flex lg:flex-col lg:fixed lg:inset-y-0 lg:left-0 lg:w-64 lg:bg-white lg:border-r lg:border-gray-200 sidebar-scrollbar overflow-y-auto">
            <!-- Brand -->
            <div class="p-6">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-3">
                    <div class="auth-brand-mark" aria-hidden="true">
                        <span class="auth-gold-left"></span>
                        <span class="auth-coral-right"></span>
                        <span class="auth-teal-base"></span>
                        <span class="auth-sky-dot"></span>
                    </div>
                    <div>
                        <p class="auth-brand-word text-[1.9rem] font-bold leading-none text-slate-900">Volunlink</p>
                        <p class="mt-1 text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-500">Morocco 2030</p>
                    </div>
                </a>
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 px-4">
                <!-- Overview -->
                <div class="mb-4 mt-4">
                    <h3 class="text-xs uppercase tracking-widest text-gray-400 px-4 mb-1">Overview</h3>
                    <a href="{{ route('admin.dashboard') }}" 
                       class="{{ request()->routeIs('admin.dashboard') ? 'bg-red-50 text-[#C1272D] font-medium border-l-4 border-[#C1272D] rounded-l-none' : 'text-gray-600 hover:bg-gray-50' }} 
                          flex items-center gap-3 px-4 py-2.5 text-sm cursor-pointer rounded-lg mx-2 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        Dashboard
                    </a>
                </div>
                
                <!-- Management -->
                <div class="mb-4">
                    <h3 class="text-xs uppercase tracking-widest text-gray-400 px-4 mb-1">Management</h3>
                    <div class="space-y-1">
                        <a href="{{ route('admin.volunteers.index') }}" 
                           class="{{ request()->routeIs('admin.volunteers.*') ? 'bg-red-50 text-[#C1272D] font-medium border-l-4 border-[#C1272D] rounded-l-none' : 'text-gray-600 hover:bg-gray-50' }} 
                              flex items-center gap-3 px-4 py-2.5 text-sm cursor-pointer rounded-lg mx-2 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Volunteers
                        </a>
                        <a href="{{ route('admin.missions.index') }}" 
                           class="{{ request()->routeIs('admin.missions.*') ? 'bg-red-50 text-[#C1272D] font-medium border-l-4 border-[#C1272D] rounded-l-none' : 'text-gray-600 hover:bg-gray-50' }} 
                              flex items-center gap-3 px-4 py-2.5 text-sm cursor-pointer rounded-lg mx-2 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Missions
                        </a>
                        <a href="{{ route('admin.sites.index') }}" 
                           class="{{ request()->routeIs('admin.sites.*') ? 'bg-red-50 text-[#C1272D] font-medium border-l-4 border-[#C1272D] rounded-l-none' : 'text-gray-600 hover:bg-gray-50' }} 
                              flex items-center gap-3 px-4 py-2.5 text-sm cursor-pointer rounded-lg mx-2 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Sites
                        </a>
                    </div>
                </div>
                
                <!-- Operations -->
                <div class="mb-4">
                    <h3 class="text-xs uppercase tracking-widest text-gray-400 px-4 mb-1">Operations</h3>
                    <a href="{{ route('admin.attendance.index') }}" 
                       class="{{ request()->routeIs('admin.attendance.*') ? 'bg-red-50 text-[#C1272D] font-medium border-l-4 border-[#C1272D] rounded-l-none' : 'text-gray-600 hover:bg-gray-50' }} 
                          flex items-center gap-3 px-4 py-2.5 text-sm cursor-pointer rounded-lg mx-2 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Attendance
                    </a>
                </div>
                
                <!-- System -->
                <div class="mb-4">
                    <h3 class="text-xs uppercase tracking-widest text-gray-400 px-4 mb-1">System</h3>
                    <a href="{{ route('profile.edit') }}" 
                       class="text-gray-600 hover:bg-gray-50 flex items-center gap-3 px-4 py-2.5 text-sm cursor-pointer rounded-lg mx-2 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Settings
                    </a>
                </div>
            </nav>
            
            <!-- Admin Info -->
            <div class="p-4 border-t border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-red-100 text-[#C1272D] flex items-center justify-center text-xs font-semibold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</p>
                        <span class="text-xs bg-[#C1272D] text-white px-2 py-0.5 rounded-full">Admin</span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Mobile sidebar overlay -->
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/50 lg:hidden" @click="sidebarOpen = false"></div>
        
        <!-- Mobile sidebar -->
        <aside x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed inset-y-0 left-0 z-50 w-64 bg-white lg:hidden">
            <!-- Mobile sidebar content (same as desktop) -->
            <div class="p-6">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-3">
                    <div class="auth-brand-mark" aria-hidden="true">
                        <span class="auth-gold-left"></span>
                        <span class="auth-coral-right"></span>
                        <span class="auth-teal-base"></span>
                        <span class="auth-sky-dot"></span>
                    </div>
                    <div>
                        <p class="auth-brand-word text-[1.9rem] font-bold leading-none text-slate-900">Volunlink</p>
                        <p class="mt-1 text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-500">Morocco 2030</p>
                    </div>
                </a>
            </div>
            
            <nav class="flex-1 px-4">
                <!-- Navigation items (same as desktop) -->
                <div class="mb-4 mt-4">
                    <h3 class="text-xs uppercase tracking-widest text-gray-400 px-4 mb-1">Overview</h3>
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:bg-gray-50 flex items-center gap-3 px-4 py-2.5 text-sm cursor-pointer rounded-lg mx-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        Dashboard
                    </a>
                </div>
                <!-- Add other mobile nav items... -->
            </nav>
        </aside>

        <!-- Main content -->
        <div class="flex-1 flex flex-col overflow-hidden lg:ml-64">
            <!-- Top navbar -->
            <header class="bg-white border-b border-gray-100 h-16 px-6 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <!-- Mobile menu button -->
                    <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    
                    <!-- Page title -->
                    <h1 class="text-xl font-semibold text-gray-800">@yield('title')</h1>
                </div>
                
                <div class="flex items-center gap-4">
                    <!-- Notifications -->
                    <div x-data="{ open: false }" class="relative">
                        <button
                            type="button"
                            @click="open = !open"
                            class="relative inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-gray-200 bg-white text-gray-500 transition hover:border-red-100 hover:bg-red-50 hover:text-[#C1272D]"
                            aria-label="Open notifications"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            @if($unreadNotificationsCount > 0)
                                <span class="absolute -right-1.5 -top-1.5 inline-flex min-h-[1.35rem] min-w-[1.35rem] items-center justify-center rounded-full bg-[#C1272D] px-1 text-[11px] font-semibold text-white">
                                    {{ $unreadNotificationsCount > 9 ? '9+' : $unreadNotificationsCount }}
                                </span>
                            @endif
                        </button>

                        <div
                            x-cloak
                            x-show="open"
                            @click.outside="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-2"
                            class="absolute right-0 z-50 mt-3 w-[24rem] overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-[0_24px_60px_-28px_rgba(15,23,42,0.35)]"
                        >
                            <div class="{{ ! $notificationsEnabled || $hasRecentNotifications ? 'border-b border-gray-100' : '' }} px-5 py-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <h2 class="text-sm font-semibold text-slate-900">Notifications</h2>
                                        @if (! $notificationsEnabled)
                                            <p class="mt-1 text-xs text-slate-500">
                                                Notifications will appear here after the database migration is run.
                                            </p>
                                        @elseif($hasRecentNotifications)
                                            <p class="mt-1 text-xs text-slate-500">
                                                {{ $unreadNotificationsCount > 0 ? $unreadNotificationsCount.' unread update(s)' : $recentNotifications->count().' recent update(s)' }}
                                            </p>
                                        @endif
                                    </div>

                                    @if($notificationsEnabled && $unreadNotificationsCount > 0)
                                        <form method="POST" action="{{ route('admin.notifications.read-all') }}">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center rounded-full border border-red-100 bg-red-50 px-3 py-1.5 text-xs font-semibold text-[#C1272D] transition hover:bg-red-100">
                                                Mark all as read
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            <div class="max-h-[24rem] overflow-y-auto px-3 py-3">
                                @if (! $notificationsEnabled)
                                    <div class="rounded-2xl border border-dashed border-amber-200 bg-amber-50 px-4 py-10 text-center">
                                        <p class="text-sm font-medium text-amber-900">Notifications are not ready yet</p>
                                        <p class="mt-1 text-xs text-amber-700">Run <span class="font-semibold">php artisan migrate</span> to create the notifications table.</p>
                                    </div>
                                @elseif($hasRecentNotifications)
                                    @foreach($recentNotifications as $notification)
                                    @php
                                        $notificationKind = data_get($notification->data, 'kind');
                                        $notificationTitle = data_get($notification->data, 'title', 'Notification');
                                        $notificationMessage = data_get($notification->data, 'message', 'A new update is available.');
                                        $notificationUrl = data_get($notification->data, 'url', route('admin.dashboard'));
                                        $badgeClasses = match ($notificationKind) {
                                            'volunteer_joined' => 'border border-red-100 bg-red-50 text-[#C1272D]',
                                            'mission_created' => 'border border-emerald-100 bg-emerald-50 text-emerald-700',
                                            default => 'border border-slate-100 bg-slate-50 text-slate-600',
                                        };
                                        $badgeLabel = match ($notificationKind) {
                                            'volunteer_joined' => 'V',
                                            'mission_created' => 'M',
                                            default => 'N',
                                        };
                                    @endphp

                                    <a
                                        href="{{ $notificationUrl }}"
                                        class="{{ $notification->read_at ? 'bg-white hover:bg-gray-50' : 'bg-red-50/50 hover:bg-red-50' }} mb-2 flex items-start gap-3 rounded-2xl border border-gray-100 px-4 py-3 transition last:mb-0"
                                    >
                                        <span class="{{ $badgeClasses }} inline-flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-2xl text-sm font-semibold">
                                            {{ $badgeLabel }}
                                        </span>

                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-start justify-between gap-3">
                                                <p class="text-sm font-semibold text-slate-900">{{ $notificationTitle }}</p>
                                                @if(is_null($notification->read_at))
                                                    <span class="mt-1 h-2.5 w-2.5 flex-shrink-0 rounded-full bg-[#C1272D]"></span>
                                                @endif
                                            </div>
                                            <p class="mt-1 text-sm leading-5 text-slate-600">{{ $notificationMessage }}</p>
                                            <p class="mt-2 text-xs font-medium text-slate-400">{{ $notification->created_at->diffForHumans() }}</p>
                                        </div>
                                    </a>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="h-6 w-px bg-gray-300"></div>
                    
                    <!-- User menu -->
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-red-100 text-[#C1272D] flex items-center justify-center text-sm font-semibold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 rounded-full border border-red-100 bg-red-50 px-3.5 py-2 text-sm font-semibold text-[#C1272D] transition hover:border-red-200 hover:bg-red-100">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1"></path>
                                </svg>
                                <span>Sign out</span>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Flash messages -->
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center justify-between">
                        <span>{{ session('success') }}</span>
                        <button @click="show = false" class="text-green-500 hover:text-green-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center justify-between">
                        <span>{{ session('error') }}</span>
                        <button @click="show = false" class="text-red-500 hover:text-red-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
