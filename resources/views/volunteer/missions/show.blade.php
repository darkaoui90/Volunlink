@extends('layouts.volunteer-dashboard')
@section('title', 'Mission Details')

@section('content')
@php
    $volunteer = auth()->user();
    $firstName = strtok($volunteer->name, ' ') ?: $volunteer->name;
    $initials = collect(preg_split('/\s+/', trim($volunteer->name)))
        ->filter()
        ->take(2)
        ->map(fn (string $part) => strtoupper(substr($part, 0, 1)))
        ->implode('');
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
                
                <a href="{{ route('volunteer.dashboard') }}" class="nav-link w-full text-left flex items-center gap-3 rounded-xl px-4 py-3 text-slate-600 font-medium transition hover:bg-gray-100 hover:text-slate-900">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7-7 7m-7-7v7a1 1 0 001 1h3m10-8v7a1 1 0 01-1 1h-3"></path>
                    </svg>
                    Dashboard
                </a>
                
                <!-- Highlighted "Upcoming Missions" state -->
                <div class="nav-link w-full text-left flex items-center gap-3 rounded-xl bg-red-50 text-[#C1272D] border-l-4 border-[#C1272D] px-4 py-3 font-semibold transition-all">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Mission Details
                </div>
                
                <a href="{{ route('volunteer.dashboard') }}" class="nav-link w-full text-left flex items-center gap-3 rounded-xl px-4 py-3 text-slate-600 font-medium transition hover:bg-gray-100 hover:text-slate-900">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6m3 6V7m3 10v-4m3-8H6a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2z"></path>
                    </svg>
                    Attendance Tracking
                </a>
                
                <a href="{{ route('volunteer.dashboard') }}" class="nav-link w-full text-left flex items-center gap-3 rounded-xl px-4 py-3 text-slate-600 font-medium transition hover:bg-gray-100 hover:text-slate-900">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Archive
                </a>

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
            
            <!-- STATIC HEADER -->
            <section class="bg-white px-6 pb-10 pt-12 sm:px-10 lg:px-14 border-b border-gray-200 sticky top-0 z-10">
                <div class="flex flex-wrap items-center justify-between gap-8">
                    
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
                        <span class="inline-block px-3 py-1 mb-3 bg-slate-100 text-slate-600 rounded-full text-[10px] font-bold tracking-widest uppercase">
                            Mission Brief
                        </span>
                        <h1 class="text-3xl font-extrabold leading-tight tracking-tight sm:text-4xl text-slate-900">
                            Mission Details
                        </h1>
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

            <!-- MISSION DETAILS CONTENT -->
            <div class="px-6 py-8 sm:px-10 lg:px-14">
                <a href="{{ route('volunteer.dashboard') }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-[#C1272D] mb-6 transition-colors group">
                    <svg class="mr-2 h-4 w-4 transform transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Dashboard
                </a>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Main Mission Information (RESTORED ORIGINAL DESIGN) -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <!-- Mission Header -->
                            <div class="bg-gradient-to-r from-[#C1272D] to-[#8B1A1F] p-6 text-white">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h2 class="text-2xl font-bold mb-2">{{ $mission->title }}</h2>
                                        <div class="flex items-center space-x-4 text-sm opacity-90">
                                            <span class="flex items-center space-x-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                {{ $mission->display_location }}
                                            </span>
                                            <span class="flex items-center space-x-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ $mission->date }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        @if($mission->date >= now()->format('Y-m-d'))
                                            <span class="bg-white/20 border border-white/30 text-white text-sm px-4 py-1.5 rounded-full font-bold uppercase tracking-wider backdrop-blur-sm shadow-sm">
                                                Upcoming
                                            </span>
                                        @else
                                            <span class="bg-black/20 text-white border border-white/10 text-sm px-4 py-1.5 rounded-full font-bold uppercase tracking-wider">
                                                Completed
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Mission Description -->
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Mission Description</h3>
                                <div class="prose prose-gray max-w-none">
                                    @if($mission->description)
                                        <p class="text-gray-700 leading-relaxed">{{ $mission->description }}</p>
                                    @else
                                        <p class="text-gray-500 italic">No description available for this mission.</p>
                                    @endif
                                </div>

                                <!-- Additional Details -->
                                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-[#C1272D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            TIME SCHEDULE
                                        </h4>
                                        <div class="space-y-2">
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-600 font-semibold">Start Time:</span>
                                                <span class="font-medium text-gray-900">{{ $mission->start_time ?? 'Not specified' }}</span>
                                            </div>
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-600 font-semibold">End Time:</span>
                                                <span class="font-medium text-gray-900">{{ $mission->end_time ?? 'Not specified' }}</span>
                                            </div>
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-600 font-semibold">Duration:</span>
                                                <span class="font-medium text-gray-900">
                                                    @if($mission->start_time && $mission->end_time)
                                                        {{ \Carbon\Carbon::parse($mission->start_time)->diffInHours(\Carbon\Carbon::parse($mission->end_time)) }} hours
                                                    @else
                                                        Not specified
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-[#C1272D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            REQUIREMENTS
                                        </h4>
                                        <div class="space-y-2">
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-600 font-semibold">Volunteers Needed:</span>
                                                <span class="font-medium text-gray-900">{{ $mission->required_volunteers ?? 0 }}</span>
                                            </div>
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-600 font-semibold">Currently Assigned:</span>
                                                <span class="font-medium text-gray-900">{{ $mission->volunteers->count() }}</span>
                                            </div>
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-600 font-semibold">Status:</span>
                                                <span class="font-medium text-gray-900">
                                                    @if($mission->date >= now()->format('Y-m-d'))
                                                        <span class="text-green-600">Active</span>
                                                    @else
                                                        <span class="text-gray-600">Completed</span>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Mission Requirements (if any) -->
                                @if($mission->requirements)
                                    <div class="mt-8">
                                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Mission Requirements</h4>
                                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                            <p class="text-blue-800 text-sm">{{ $mission->requirements }}</p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Action Buttons -->
                                <div class="mt-8 flex flex-col sm:flex-row gap-4">
                                    @if($mission->date >= now()->format('Y-m-d'))
                                        <button class="w-16 h-12 flex items-center justify-center border border-transparent bg-[#C1272D] text-white rounded-lg hover:bg-[#8B1A1F] transition-colors shadow-sm">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                            </svg>
                                        </button>
                                        <button class="flex-1 px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            Add to Calendar
                                        </button>
                                    @endif
                                    <button class="flex-1 px-6 py-3 border border-gray-300 bg-white text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                        Download PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar Information -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- Quick Info Card -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                            <h3 class="text-sm font-extrabold uppercase tracking-widest text-slate-900 mb-6">Quick Overview</h3>
                            <div class="space-y-5">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-red-50 border border-red-100 rounded-xl flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5 text-[#C1272D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Location</p>
                                        <p class="font-extrabold text-slate-900 mt-0.5">{{ $mission->display_location }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-green-50 border border-green-100 rounded-xl flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Date</p>
                                        <p class="font-extrabold text-slate-900 mt-0.5">{{ $mission->date }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-blue-50 border border-blue-100 rounded-xl flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Time</p>
                                        <p class="font-extrabold text-slate-900 mt-0.5">{{ $mission->start_time ?? 'Not set' }} - {{ $mission->end_time ?? 'Not set' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Assigned Volunteers -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                            <h3 class="text-sm font-extrabold uppercase tracking-widest text-slate-900 mb-6 flex items-center justify-between">
                                Your Team
                                <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full text-xs">{{ $mission->volunteers->count() }}</span>
                            </h3>
                            <div class="space-y-4">
                                @if($mission->volunteers->count() > 0)
                                    @foreach($mission->volunteers->take(5) as $v)
                                        @php
                                            $vInitials = collect(preg_split('/\s+/', trim($v->name)))->filter()->take(2)->map(fn($p) => strtoupper(substr($p,0,1)))->implode('');
                                        @endphp
                                        <div class="flex items-center space-x-3">
                                            <div class="w-9 h-9 bg-gray-100 rounded-full flex items-center justify-center text-xs font-bold text-gray-500 border border-gray-200 shadow-sm">
                                                {{ $vInitials }}
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-sm font-bold text-slate-900 truncate">{{ $v->name }}</p>
                                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 truncate">Volunteer</p>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if($mission->volunteers->count() > 5)
                                        <div class="text-center pt-3 border-t border-gray-100 mt-4">
                                            <p class="text-xs font-bold text-slate-500 hover:text-[#C1272D] cursor-pointer transition-colors">+{{ $mission->volunteers->count() - 5 }} more teammates</p>
                                        </div>
                                    @endif
                                @else
                                    <p class="text-slate-500 text-sm font-medium">No team assigned yet.</p>
                                @endif
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                            <h3 class="text-sm font-extrabold uppercase tracking-widest text-slate-900 mb-6">Need Support?</h3>
                            <div class="space-y-3">
                                <button class="w-full px-4 py-3 bg-blue-50 text-blue-700 font-bold rounded-xl border border-blue-100 hover:bg-blue-100 transition-colors shadow-sm">
                                    <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    Message Coordinator
                                </button>
                                <button class="w-full px-4 py-3 bg-white border border-gray-200 text-slate-600 font-bold rounded-xl hover:bg-gray-50 transition-colors shadow-sm">
                                    <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Report Issue
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <footer class="mt-12 py-6 text-center text-xs font-bold text-slate-400 border-t border-gray-200">
                    &copy; {{ date('Y') }} Volunlink - Morocco 2030 World Cup. All rights reserved.
                </footer>
            </div>
        </main>
    </div>
</div>
@endsection
