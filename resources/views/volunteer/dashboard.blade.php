@extends('layouts.volunteer-dashboard')
@section('title', 'Volunteer Dashboard')

@section('content')
@php
    $volunteer = auth()->user();
    $today = now()->format('Y-m-d');
    $firstName = strtok($volunteer->name, ' ') ?: $volunteer->name;
    $totalAssigned = $volunteer->missions()->count();
    $upcomingMissions = $volunteer->missions()->with('site')->where('date', '>=', $today)->orderBy('date')->get();
    $allMissions = $volunteer->missions()->with('site')->orderBy('date')->get();
    $upcomingCount = $upcomingMissions->count();
    $completedCount = $volunteer->missions()->where('date', '<', $today)->count();
    $absenceCount = $volunteer->missions()->wherePivot('status', 'absent')->count();
    $lateCount = $volunteer->missions()->wherePivot('status', 'late')->count();
    $totalLateMinutes = (int) $volunteer->missions()->wherePivot('status', 'late')->sum('mission_user.late_minutes');
    $nextMission = $upcomingMissions->first();
    $initials = collect(preg_split('/\s+/', trim($volunteer->name)))
        ->filter()
        ->take(2)
        ->map(fn (string $part) => strtoupper(substr($part, 0, 1)))
        ->implode('');
    $attendancePalette = [
        'assigned' => 'bg-slate-100 text-slate-700',
        'present' => 'bg-emerald-100 text-emerald-700',
        'absent' => 'bg-rose-100 text-rose-700',
        'late' => 'bg-amber-100 text-amber-700',
    ];
@endphp

<style>
    /* Admin Brand Logo Styles */
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
    
    /* Scrollbar */
    .custom-scroll::-webkit-scrollbar { width: 6px; }
    .custom-scroll::-webkit-scrollbar-track { background: transparent; }
    .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>

<div class="h-screen overflow-hidden bg-slate-50">
    <div class="mx-auto flex h-full max-w-[1600px]">
        
        <!-- SIDEBAR -->
        <aside class="hidden w-[280px] shrink-0 bg-white border-r border-gray-200 lg:flex lg:flex-col h-full z-20 shadow-sm">
            <!-- Brand -->
            <div class="px-7 py-8">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-3">
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
            <nav class="mt-4 flex-1 space-y-1.5 px-4 overflow-y-auto custom-scroll">
                <h3 class="text-[10px] uppercase tracking-[0.2em] font-bold text-slate-400 px-4 mb-3">Main Menu</h3>
                
                <button onclick="switchTab('dashboard', this)" type="button" class="nav-link w-full text-left flex items-center gap-3 rounded-xl bg-red-50 text-[#C1272D] border-l-4 border-[#C1272D] px-4 py-3 font-semibold transition-all">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7-7 7m-7-7v7a1 1 0 001 1h3m10-8v7a1 1 0 01-1 1h-3"></path>
                    </svg>
                    Dashboard
                </button>
                
                <button onclick="switchTab('upcoming', this)" type="button" class="nav-link w-full text-left flex items-center gap-3 rounded-xl px-4 py-3 text-slate-600 font-medium transition hover:bg-gray-100 hover:text-slate-900">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Upcoming Missions
                </button>
                
                <button onclick="switchTab('attendance', this)" type="button" class="nav-link w-full text-left flex items-center gap-3 rounded-xl px-4 py-3 text-slate-600 font-medium transition hover:bg-gray-100 hover:text-slate-900">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6m3 6V7m3 10v-4m3-8H6a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2z"></path>
                    </svg>
                    Attendance Tracking
                </button>
                
                <button onclick="switchTab('archive', this)" type="button" class="nav-link w-full text-left flex items-center gap-3 rounded-xl px-4 py-3 text-slate-600 font-medium transition hover:bg-gray-100 hover:text-slate-900">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Archive
                </button>

                <h3 class="text-[10px] uppercase tracking-[0.2em] font-bold text-slate-400 px-4 mt-8 mb-3">System</h3>
                
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-slate-600 font-medium transition hover:bg-gray-100 hover:text-slate-900">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Account Settings
                </a>
            </nav>

            <div class="mt-auto p-4 border-t border-gray-100">
                <div class="flex items-center justify-between gap-2 rounded-xl bg-gray-50 p-3 border border-gray-100">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-[#C1272D] text-sm font-bold text-white shadow-sm">
                            {{ $initials }}
                        </div>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-bold text-slate-800">{{ $volunteer->name }}</p>
                            <p class="truncate text-[10px] font-bold uppercase tracking-widest text-slate-400 mt-0.5">Volunteer Badge</p>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('logout') }}" class="m-0 p-0 shrink-0">
                        @csrf
                        <button type="submit" class="flex h-9 w-9 items-center justify-center rounded-lg bg-white border border-gray-200 text-slate-500 shadow-sm transition-all hover:bg-red-50 hover:text-[#C1272D] hover:border-red-100" title="Log Out">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- MAIN SCROLLABLE CONTENT -->
        <main class="flex-1 h-full overflow-y-auto custom-scroll relative bg-slate-50">
            
            <!-- STATIC HEADER (Visible on all tabs) -->
            <section class="bg-white px-6 pb-10 pt-10 sm:px-10 lg:px-14 border-b border-gray-200 sticky top-0 z-10">
                <div class="flex flex-wrap items-center justify-between gap-8">
                    
                    <!-- Mobile Brand Header -->
                    <div class="lg:hidden w-full flex items-center justify-between mb-2">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-3">
                            <div class="auth-brand-mark" aria-hidden="true" style="transform: scale(0.8); transform-origin: left center;">
                                <span class="auth-gold-left"></span>
                                <span class="auth-coral-right"></span>
                                <span class="auth-teal-base"></span>
                                <span class="auth-sky-dot"></span>
                            </div>
                            <div>
                                <p class="auth-brand-word text-xl font-bold leading-none text-slate-900">Volunlink</p>
                            </div>
                        </a>
                    </div>

                    <div class="max-w-2xl">
                        <span class="inline-block px-3 py-1 mb-3 bg-red-50 text-[#C1272D] rounded-full text-[10px] font-bold tracking-widest uppercase">
                            Event Operations
                        </span>
                        <h1 class="text-3xl font-extrabold leading-tight tracking-tight sm:text-4xl text-slate-900">
                            Volunteer Portal
                        </h1>
                        <p class="mt-3 text-sm text-slate-500 sm:text-base font-medium">
                            Welcome to the nerve center of the Morocco 2030 World Cup. Track your assignments, review your schedule, and check your latest status updates.
                        </p>
                    </div>

                    <div class="w-full sm:w-auto">
                        <!-- Clean Pill Profile Card with Logout -->
                        <div class="flex items-center gap-4 bg-white border border-gray-200 rounded-full py-2 px-3 pr-2 shadow-sm">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-[#C1272D] text-lg font-bold text-white shadow-sm">
                                {{ $initials }}
                            </div>
                            <div class="pr-3 border-r border-gray-100">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Welcome Back</p>
                                <p class="text-base font-bold text-slate-900 leading-tight mt-0.5">{{ $firstName }}</p>
                            </div>
                            
                            <form method="POST" action="{{ route('logout') }}" class="m-0 p-0 mr-1">
                                @csrf
                                <button type="submit" class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-50 text-slate-500 transition-all hover:bg-red-50 hover:text-[#C1272D]" title="Log Out">
                                    <svg class="h-5 w-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ===================== -->
            <!-- TAB CONTENT SECTIONS  -->
            <!-- ===================== -->

            <!-- TAB 1: DASHBOARD -->
            <div id="tab-dashboard" class="tab-content px-6 py-10 sm:px-10 lg:px-14">
                
                <!-- STATS CARDS -->
                <div class="grid gap-6 md:grid-cols-3">
                    <!-- Card 1: Total Assigned -->
                    <div class="group rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition-transform hover:-translate-y-1 hover:shadow-md">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Total Missions</p>
                                <p class="mt-1 text-4xl font-extrabold text-slate-900">{{ $totalAssigned }}</p>
                            </div>
                            <div class="flex h-20 w-20 items-center justify-center rounded-xl bg-gray-50 border border-gray-100">
                                <img src="{{ asset('images/world_cup_icon.png') }}" class="h-16 w-16 object-contain" alt="World Cup">
                            </div>
                        </div>
                        <div class="mt-5 flex items-center gap-2 text-sm font-bold text-emerald-600">
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-100">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </span>
                            You are fully registered
                        </div>
                    </div>

                    <!-- Card 2: Upcoming -->
                    <div class="group rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition-transform hover:-translate-y-1 hover:shadow-md">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Upcoming Shifts</p>
                                <p class="mt-1 text-4xl font-extrabold text-slate-900">{{ $upcomingCount }}</p>
                            </div>
                            <div class="flex h-20 w-20 items-center justify-center rounded-xl bg-gray-50 border border-gray-100">
                                <img src="{{ asset('images/active_missions_icon.png') }}" class="h-16 w-16 object-contain" alt="Active Missions">
                            </div>
                        </div>
                        <div class="mt-5 flex items-center gap-2 text-sm font-bold text-amber-600">
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-amber-100">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </span>
                            Future assignments
                        </div>
                    </div>

                    <!-- Card 3: Completed -->
                    <div class="group rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition-transform hover:-translate-y-1 hover:shadow-md">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Completed</p>
                                <p class="mt-1 text-4xl font-extrabold text-slate-900">{{ $completedCount }}</p>
                            </div>
                            <div class="flex h-20 w-20 items-center justify-center rounded-xl bg-gray-50 border border-gray-100">
                                <img src="{{ asset('images/attendance_icon.png') }}" class="h-16 w-16 object-contain" alt="Attendance">
                            </div>
                        </div>
                        <div class="mt-5 flex items-center gap-2 text-sm font-bold text-blue-600">
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-blue-100">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </span>
                            Finished shifts
                        </div>
                    </div>
                </div>

                <!-- Next Mission Spotlight (Changed to clean white card, black text) -->
                <section class="mt-10 rounded-2xl border border-gray-200 bg-white p-10 sm:p-12 shadow-sm">
                    <div class="relative z-10" style="padding: 30px;">
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#C1272D]">Priority Notification</p>
                        
                        @if ($nextMission)
                            <h2 class="mt-2 text-2xl font-extrabold text-slate-900">Your Next Mission</h2>
                            <p class="mt-1 text-lg font-medium text-slate-600">{{ $nextMission->title }}</p>
                            
                            <div class="mt-8 space-y-4 text-sm">
                                <div class="flex items-center gap-4 rounded-xl border border-gray-100 bg-gray-50 px-6 py-5 sm:px-7">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white shadow-sm border border-gray-100 text-[#C1272D]">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] font-bold text-slate-500 mb-0.5 uppercase tracking-wider">Location</span>
                                        <span class="block font-bold text-slate-900 text-sm">{{ $nextMission->display_location }}</span>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-4 rounded-xl border border-gray-100 bg-gray-50 px-6 py-5 sm:px-7">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white shadow-sm border border-gray-100 text-[#C1272D]">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] font-bold text-slate-500 mb-0.5 uppercase tracking-wider">Date & Time</span>
                                        <span class="block font-bold text-slate-900 text-sm">{{ $nextMission->date }} | {{ $nextMission->start_time ?? 'TBD' }} - {{ $nextMission->end_time ?? 'TBD' }}</span>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('volunteer.missions.show', $nextMission->id) }}" class="mt-10 inline-flex w-full items-center justify-center rounded-xl bg-slate-900 px-5 py-4 text-sm font-extrabold text-white hover:bg-slate-800 transition-colors">
                                Open Mission Details
                            </a>
                        @else
                            <div class="flex flex-col items-center justify-center h-full py-10 text-center mt-4 border border-gray-200 rounded-2xl bg-gray-50">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white border border-gray-200 text-gray-400 mb-4 shadow-sm">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                </div>
                                <h2 class="text-lg font-bold text-slate-900">No Scheduled Missions</h2>
                                <p class="mt-2 text-xs font-medium leading-relaxed text-slate-500 max-w-[250px] mx-auto">
                                    Your dashboard is clear for now. We will notify you when a new shift is assigned.
                                </p>
                            </div>
                        @endif
                    </div>
                </section>
            </div>


            <!-- TAB 2: UPCOMING MISSIONS -->
            <div id="tab-upcoming" class="tab-content hidden px-6 py-10 sm:px-10 lg:px-14">
                <section class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm" style="padding: 30px;">
                    <div class="flex flex-wrap items-end justify-between gap-4 border-b border-gray-100 pb-5">
                        <div>
                            <h2 class="text-2xl font-extrabold text-slate-900">Upcoming Missions</h2>
                            <p class="mt-1 text-sm font-medium text-slate-500">Your next assigned missions with their key details.</p>
                        </div>
                        <div class="flex items-center gap-2 text-sm font-bold text-slate-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>{{ $upcomingCount }} mission{{ $upcomingCount === 1 ? '' : 's' }}</span>
                        </div>
                    </div>

                    @if ($upcomingMissions->isEmpty())
                        <div class="mt-6 rounded-2xl border border-gray-200 bg-gray-50 px-6 py-12 text-center">
                            <p class="text-lg font-bold text-slate-900">No upcoming missions</p>
                            <p class="mt-2 text-sm font-medium text-slate-500">You do not have any future assignments right now.</p>
                        </div>
                    @else
                        <div class="mt-6 grid gap-6 xl:grid-cols-2">
                            @foreach ($upcomingMissions as $mission)
                                @php
                                    $attendanceClass = $attendancePalette[$mission->pivot->status ?? 'assigned'] ?? $attendancePalette['assigned'];
                                @endphp
                                <article class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm hover:shadow-md transition-shadow">
                                    <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-5">
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <h3 class="text-xl font-bold text-[#C1272D]">{{ $mission->title }}</h3>
                                                <div class="mt-3 flex flex-wrap items-center gap-4 text-sm font-medium text-slate-600">
                                                    <span class="flex items-center gap-1.5">
                                                        <svg class="h-4 w-4 text-[#C1272D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        </svg>
                                                        <span class="text-slate-900 font-bold">{{ $mission->display_location }}</span>
                                                    </span>
                                                    <span class="flex items-center gap-1.5">
                                                        <svg class="h-4 w-4 text-[#C1272D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                        <span class="text-slate-900 font-bold">{{ $mission->date }}</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="px-6 py-5">
                                        <div class="grid gap-4 text-sm text-slate-700 sm:grid-cols-2">
                                            <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Time Window</p>
                                                <p class="mt-1 font-bold text-slate-900">{{ $mission->start_time ?? 'Not set' }} - {{ $mission->end_time ?? 'Not set' }}</p>
                                            </div>
                                            <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Staffing</p>
                                                <p class="mt-1 font-bold text-slate-900">{{ $mission->required_volunteers ?? 0 }} needed</p>
                                            </div>
                                        </div>

                                        <div class="mt-5 flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 pt-5">
                                            <span class="inline-flex items-center rounded-lg px-3 py-1 text-xs font-bold uppercase tracking-wider {{ $attendanceClass }}">
                                                {{ $mission->pivot->status ?? 'assigned' }}
                                            </span>
                                            <a href="{{ route('volunteer.missions.show', $mission->id) }}" class="text-sm font-bold text-[#C1272D] hover:underline">
                                                View Details →
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endif
                </section>
            </div>


            <!-- TAB 3: ATTENDANCE TRACKING -->
            <div id="tab-attendance" class="tab-content hidden px-6 py-10 sm:px-10 lg:px-14">
                <section class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm" style="padding: 30px;">
                    <div class="flex flex-wrap items-end justify-between gap-4 border-b border-gray-100 pb-5">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#C1272D]">Performance Data</p>
                            <h2 class="mt-2 text-2xl font-extrabold text-slate-900">Attendance Summary</h2>
                        </div>
                        <div class="rounded-full bg-gray-50 px-4 py-1.5 text-xs font-bold text-slate-500 border border-gray-200">
                            Real-time tracking
                        </div>
                    </div>

                    <div class="mt-8 grid gap-5 sm:grid-cols-3">
                        <div class="rounded-2xl border border-gray-200 bg-gray-50 p-6 shadow-sm">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white border border-gray-200 text-rose-600 mb-4">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <p class="text-sm font-bold text-slate-500">Total Absences</p>
                            <p class="mt-1 text-3xl font-extrabold text-slate-900">{{ $absenceCount }}</p>
                        </div>
                        
                        <div class="rounded-2xl border border-gray-200 bg-gray-50 p-6 shadow-sm">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white border border-gray-200 text-amber-500 mb-4">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <p class="text-sm font-bold text-slate-500">Late Attendances</p>
                            <p class="mt-1 text-3xl font-extrabold text-slate-900">{{ $lateCount }}</p>
                        </div>
                        
                        <div class="rounded-2xl border border-gray-200 bg-gray-50 p-6 shadow-sm">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white border border-gray-200 text-slate-600 mb-4">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <p class="text-sm font-bold text-slate-500">Total Late Minutes</p>
                            <p class="mt-1 text-3xl font-extrabold text-slate-900">{{ $totalLateMinutes }}<span class="text-lg font-bold text-slate-400 ml-1">m</span></p>
                        </div>
                    </div>
                </section>
            </div>


            <!-- TAB 4: ARCHIVE -->
            <div id="tab-archive" class="tab-content hidden px-6 py-10 sm:px-10 lg:px-14">
                <section class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm" style="padding: 30px;">
                    <div class="flex flex-wrap items-end justify-between gap-4 border-b border-gray-100 pb-5">
                        <div>
                            <h2 class="text-2xl font-extrabold text-slate-900">Your Full Roster</h2>
                            <p class="mt-1 text-sm font-medium text-slate-500">Every assigned mission and historical record.</p>
                        </div>
                    </div>

                    @if ($allMissions->isEmpty())
                        <div class="mt-8 rounded-2xl border border-gray-200 bg-gray-50 px-6 py-16 text-center">
                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-xl bg-white border border-gray-200 text-gray-400 mb-4">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <p class="text-lg font-bold text-slate-900">No records found</p>
                            <p class="mt-1 text-sm font-medium text-slate-500">You haven't been attached to any operations yet.</p>
                        </div>
                    @else
                        <div class="mt-6 overflow-x-auto">
                            <table class="min-w-full text-left border-collapse">
                                <thead class="bg-gray-50 text-[10px] font-bold uppercase tracking-widest text-slate-500 border-b border-gray-200">
                                    <tr>
                                        <th class="px-6 py-4 rounded-tl-xl">Mission Title</th>
                                        <th class="px-6 py-4">Schedule</th>
                                        <th class="px-6 py-4">Venue</th>
                                        <th class="px-6 py-4">Log Status</th>
                                        <th class="px-6 py-4 rounded-tr-xl">Timeline</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-sm font-medium text-slate-600">
                                    @foreach ($allMissions as $mission)
                                        @php
                                            $attendanceClass = $attendancePalette[$mission->pivot->status ?? 'assigned'] ?? $attendancePalette['assigned'];
                                            $phaseClass = $mission->date >= $today
                                                ? 'border border-emerald-200 bg-emerald-50 text-emerald-700'
                                                : 'border border-slate-200 bg-slate-50 text-slate-600';
                                        @endphp
                                        <tr class="transition hover:bg-gray-50 group">
                                            <td class="px-6 py-4">
                                                <p class="font-bold text-slate-900 group-hover:text-[#C1272D] transition-colors">{{ $mission->title }}</p>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex flex-col">
                                                    <span class="text-slate-900 font-bold">{{ $mission->date }}</span>
                                                    <span class="text-xs text-slate-500 mt-0.5">{{ $mission->start_time ?? 'TBD' }} - {{ $mission->end_time ?? 'TBD' }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-slate-900 font-bold">{{ $mission->display_location }}</td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider {{ $attendanceClass }}">
                                                    {{ $mission->pivot->status ?? 'assigned' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider {{ $phaseClass }}">
                                                    {{ $mission->date >= $today ? 'Upcoming' : 'Completed' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </section>
            </div>
            
            <!-- Footer -->
            <footer class="mt-10 py-6 text-center text-xs font-bold text-slate-400 border-t border-gray-200">
                &copy; {{ date('Y') }} Volunlink - Morocco 2030 World Cup. All rights reserved.
            </footer>
        </main>
    </div>
</div>

<script>
    function switchTab(tabId, element) {
        // 1. Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(function(el) {
            el.classList.add('hidden');
        });
        
        // 2. Show the target tab content
        document.getElementById('tab-' + tabId).classList.remove('hidden');
        
        // 3. Reset all nav link styles to inactive state
        document.querySelectorAll('.nav-link').forEach(function(el) {
            el.className = "nav-link w-full text-left flex items-center gap-3 rounded-xl px-4 py-3 text-slate-600 font-medium transition hover:bg-gray-100 hover:text-slate-900";
        });
        
        // 4. Apply active styles to clicked link
        element.className = "nav-link w-full text-left flex items-center gap-3 rounded-xl bg-red-50 text-[#C1272D] border-l-4 border-[#C1272D] px-4 py-3 font-semibold transition-all";
    }
</script>
@endsection
