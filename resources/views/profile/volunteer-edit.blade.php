@extends('layouts.volunteer-dashboard')
@section('title', 'Account Settings')

@push('head')
    <style>
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

        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
@endpush

@section('content')
@php
    $volunteer = $user;
    $today = now()->toDateString();
    $firstName = strtok($volunteer->name, ' ') ?: $volunteer->name;
    $initials = collect(preg_split('/\s+/', trim($volunteer->name)))
        ->filter()
        ->take(2)
        ->map(fn (string $part) => strtoupper(substr($part, 0, 1)))
        ->implode('');

    $missionCount = $volunteer->missions()->count();
    $upcomingCount = $volunteer->missions()->where('date', '>=', $today)->count();
    $completedCount = $volunteer->missions()->where('date', '<', $today)->count();
    $profileFields = collect([
        $volunteer->name,
        $volunteer->email,
        $volunteer->phone,
        $volunteer->city,
        $volunteer->languages,
        $volunteer->skills,
        $volunteer->availability,
    ]);
    $profileCompletion = (int) round(($profileFields->filter(fn ($value) => filled($value))->count() / max($profileFields->count(), 1)) * 100);
    $normalizeList = function ($value) {
        if (blank($value)) {
            return collect();
        }

        if ($value instanceof \Illuminate\Support\Collection) {
            return $value->filter();
        }

        if (is_array($value)) {
            return collect($value)->filter();
        }

        $decoded = is_string($value) ? json_decode($value, true) : null;

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return collect($decoded)
                ->map(fn ($item) => is_string($item) ? trim($item) : $item)
                ->filter();
        }

        return collect(explode(',', (string) $value))
            ->map(fn (string $item) => trim($item))
            ->filter();
    };

    $formatAvailability = fn (string $item) => match (strtolower(trim($item))) {
        'weekdays' => 'Weekdays',
        'weekends' => 'Weekends',
        'full-time', 'full time' => 'Full Time',
        'on-demand', 'on demand' => 'On Demand',
        default => \Illuminate\Support\Str::headline($item),
    };

    $availabilityList = $normalizeList($volunteer->availability)
        ->map(fn ($item) => is_string($item) ? $formatAvailability($item) : $item)
        ->filter()
        ->take(4)
        ->values();
    $skillList = $normalizeList($volunteer->skills)
        ->map(fn ($item) => is_string($item) ? trim($item) : $item)
        ->filter()
        ->take(6)
        ->values();
    $languageList = $normalizeList($volunteer->languages)
        ->map(fn ($item) => is_string($item) ? trim($item) : $item)
        ->filter()
        ->take(4)
        ->values();
@endphp

<div class="h-screen overflow-hidden bg-slate-50">
    <div class="mx-auto flex h-full max-w-[1600px]">
        <aside class="hidden h-full w-[280px] shrink-0 border-r border-gray-200 bg-white shadow-sm lg:flex lg:flex-col">
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

            <nav class="mt-4 flex-1 space-y-1.5 overflow-y-auto px-4 custom-scroll">
                <h3 class="mb-3 px-4 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">Main Menu</h3>

                <a href="{{ route('volunteer.dashboard') }}" class="flex items-center gap-3 rounded-xl px-4 py-3 text-slate-600 font-medium transition hover:bg-gray-100 hover:text-slate-900">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7-7 7m-7-7v7a1 1 0 001 1h3m10-8v7a1 1 0 01-1 1h-3"></path>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 rounded-xl border-l-4 border-[#C1272D] bg-red-50 px-4 py-3 font-semibold text-[#C1272D] transition-all">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Account Settings
                </a>

                <h3 class="mb-3 mt-8 px-4 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">Volunteer Status</h3>

                <div class="rounded-2xl border border-gray-200 bg-gray-50 px-4 py-4">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">Assigned Missions</p>
                    <p class="mt-2 text-3xl font-extrabold text-slate-900">{{ $missionCount }}</p>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-gray-50 px-4 py-4">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">Upcoming</p>
                    <p class="mt-2 text-lg font-bold text-slate-900">{{ $upcomingCount }} mission{{ $upcomingCount === 1 ? '' : 's' }}</p>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-gray-50 px-4 py-4">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">Completed</p>
                    <p class="mt-2 text-lg font-bold text-slate-900">{{ $completedCount }} mission{{ $completedCount === 1 ? '' : 's' }}</p>
                </div>
            </nav>

            <div class="mt-auto border-t border-gray-100 p-4">
                <div class="flex items-center gap-3 rounded-xl border border-gray-100 bg-gray-50 p-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-[#C1272D] text-sm font-bold text-white">
                        {{ $initials }}
                    </div>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-bold text-slate-800">{{ $volunteer->name }}</p>
                        <p class="truncate text-xs font-medium text-slate-500">Volunteer Badge</p>
                    </div>
                </div>
            </div>
        </aside>

        <main class="relative h-full flex-1 overflow-y-auto bg-slate-50 custom-scroll">
            <section class="sticky top-0 z-10 border-b border-gray-200 bg-white px-6 pb-10 pt-10 sm:px-10 lg:px-14">
                <div class="flex flex-wrap items-center justify-between gap-8">
                    <div class="mb-2 flex w-full items-center justify-between lg:hidden">
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
                        <span class="mb-3 inline-block rounded-full bg-red-50 px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-[#C1272D]">
                            Event Operations
                        </span>
                        <h1 class="text-3xl font-extrabold leading-tight tracking-tight text-slate-900 sm:text-4xl">
                            Volunteer Portal
                        </h1>
                        <p class="mt-3 text-sm font-medium text-slate-500 sm:text-base">
                            Manage your volunteer identity with the same control center you use for missions. Update your contact details, keep your field profile current, and secure your access.
                        </p>
                    </div>

                    <div class="w-full sm:w-auto">
                        <div class="flex items-center gap-4 rounded-full border border-gray-200 bg-white px-3 py-2 pr-6 shadow-sm">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-[#C1272D] text-lg font-bold text-white shadow-sm">
                                {{ $initials }}
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Volunteer Badge</p>
                                <p class="mt-0.5 text-base font-bold leading-tight text-slate-900">{{ $firstName }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <div class="px-6 py-10 sm:px-10 lg:px-14">
                <div class="grid gap-6 md:grid-cols-3">
                    <div class="group rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition-transform hover:-translate-y-1 hover:shadow-md">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Profile Readiness</p>
                                <p class="mt-1 text-4xl font-extrabold text-slate-900">{{ $profileCompletion }}%</p>
                            </div>
                            <div class="flex h-20 w-20 items-center justify-center rounded-xl border border-gray-100 bg-gray-50 text-[#C1272D]">
                                <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l3.414 3.414A1 1 0 0117 7.414V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-5 flex items-center gap-2 text-sm font-bold text-emerald-600">
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-100">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </span>
                            Operational details are being tracked
                        </div>
                    </div>

                    <div class="group rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition-transform hover:-translate-y-1 hover:shadow-md">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Upcoming Shifts</p>
                                <p class="mt-1 text-4xl font-extrabold text-slate-900">{{ $upcomingCount }}</p>
                            </div>
                            <div class="flex h-20 w-20 items-center justify-center rounded-xl border border-gray-100 bg-gray-50 text-[#C1272D]">
                                <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-5 flex items-center gap-2 text-sm font-bold text-amber-600">
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-amber-100">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3"></path>
                                </svg>
                            </span>
                            Stay ready for the next operation
                        </div>
                    </div>

                    <div class="group rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition-transform hover:-translate-y-1 hover:shadow-md">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Current Base</p>
                                <p class="mt-1 text-2xl font-extrabold text-slate-900">{{ $volunteer->city ?: 'Not Set' }}</p>
                            </div>
                            <div class="flex h-20 w-20 items-center justify-center rounded-xl border border-gray-100 bg-gray-50 text-[#C1272D]">
                                <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-5 flex items-center gap-2 text-sm font-bold text-slate-600">
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-slate-100">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12h6"></path>
                                </svg>
                            </span>
                            Profile routing and logistics reference
                        </div>
                    </div>
                </div>

                <div class="mt-10 grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
                    <div class="space-y-6">
                        <section class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm" style="padding: 30px;">
                            <div class="flex flex-wrap items-start justify-between gap-4 border-b border-gray-100 pb-5">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#C1272D]">Identity</p>
                                    <h2 class="mt-2 text-2xl font-extrabold text-slate-900">Personal Information</h2>
                                    <p class="mt-2 text-sm font-medium text-slate-500">These details help the operations team identify you quickly and contact you for mission changes.</p>
                                </div>

                                @if (session('status') === 'profile-updated')
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700">
                                        Profile saved
                                    </span>
                                @endif
                            </div>

                            <form method="POST" action="{{ route('profile.update') }}" class="mt-8 space-y-6">
                                @csrf
                                @method('PATCH')

                                <div class="grid gap-5 md:grid-cols-2">
                                    <div>
                                        <label for="name" class="mb-2 block text-sm font-bold text-slate-700">Full name</label>
                                        <input id="name" name="name" type="text" required autofocus autocomplete="name" value="{{ old('name', $user->name) }}" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3.5 text-slate-900 shadow-sm outline-none transition focus:border-[#C1272D] focus:bg-white focus:ring-4 focus:ring-red-100">
                                        @error('name')
                                            <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="email" class="mb-2 block text-sm font-bold text-slate-700">Email address</label>
                                        <input id="email" name="email" type="email" required autocomplete="username" value="{{ old('email', $user->email) }}" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3.5 text-slate-900 shadow-sm outline-none transition focus:border-[#C1272D] focus:bg-white focus:ring-4 focus:ring-red-100">
                                        @error('email')
                                            <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="phone" class="mb-2 block text-sm font-bold text-slate-700">Phone number</label>
                                        <input id="phone" name="phone" type="text" autocomplete="tel" value="{{ old('phone', $user->phone) }}" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3.5 text-slate-900 shadow-sm outline-none transition focus:border-[#C1272D] focus:bg-white focus:ring-4 focus:ring-red-100">
                                        @error('phone')
                                            <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="city" class="mb-2 block text-sm font-bold text-slate-700">City</label>
                                        <input id="city" name="city" type="text" autocomplete="address-level2" value="{{ old('city', $user->city) }}" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3.5 text-slate-900 shadow-sm outline-none transition focus:border-[#C1272D] focus:bg-white focus:ring-4 focus:ring-red-100">
                                        @error('city')
                                            <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid gap-4 sm:grid-cols-3">
                                    <div class="rounded-xl border border-gray-100 bg-gray-50 px-5 py-4">
                                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500">Availability</p>
                                        @if ($availabilityList->isNotEmpty())
                                            <div class="mt-3 flex flex-wrap gap-2">
                                                @foreach ($availabilityList as $availability)
                                                    <span class="inline-flex rounded-full bg-white px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-slate-700 ring-1 ring-gray-200">
                                                        {{ $availability }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="mt-2 text-sm font-bold text-slate-900">Not set</p>
                                        @endif
                                    </div>
                                    <div class="rounded-xl border border-gray-100 bg-gray-50 px-5 py-4">
                                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500">Languages</p>
                                        @if ($languageList->isNotEmpty())
                                            <div class="mt-3 flex flex-wrap gap-2">
                                                @foreach ($languageList as $language)
                                                    <span class="inline-flex rounded-full bg-white px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-slate-700 ring-1 ring-gray-200">
                                                        {{ $language }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="mt-2 text-sm font-bold text-slate-900">Not set</p>
                                        @endif
                                    </div>
                                    <div class="rounded-xl border border-gray-100 bg-gray-50 px-5 py-4">
                                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500">Skills</p>
                                        @if ($skillList->isNotEmpty())
                                            <div class="mt-3 flex flex-wrap gap-2">
                                                @foreach ($skillList as $skill)
                                                    <span class="inline-flex rounded-full bg-white px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-slate-700 ring-1 ring-gray-200">
                                                        {{ $skill }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="mt-2 text-sm font-bold text-slate-900">Not set</p>
                                        @endif
                                    </div>
                                </div>

                                <button type="submit" class="inline-flex items-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-slate-800">
                                    Save Profile Changes
                                </button>
                            </form>
                        </section>

                        <section class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm" style="padding: 30px;">
                            <div class="flex flex-wrap items-start justify-between gap-4 border-b border-gray-100 pb-5">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#C1272D]">Security</p>
                                    <h2 class="mt-2 text-2xl font-extrabold text-slate-900">Update Password</h2>
                                    <p class="mt-2 text-sm font-medium text-slate-500">Keep your account protected with a strong password that only you know.</p>
                                </div>

                                @if (session('status') === 'password-updated')
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700">
                                        Password updated
                                    </span>
                                @endif
                            </div>

                            <form method="POST" action="{{ route('password.update') }}" class="mt-8 space-y-5">
                                @csrf
                                @method('PUT')

                                <div>
                                    <label for="current_password" class="mb-2 block text-sm font-bold text-slate-700">Current password</label>
                                    <input id="current_password" name="current_password" type="password" autocomplete="current-password" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3.5 text-slate-900 shadow-sm outline-none transition focus:border-[#C1272D] focus:bg-white focus:ring-4 focus:ring-red-100">
                                    @if ($errors->updatePassword->has('current_password'))
                                        <p class="mt-2 text-sm font-medium text-red-600">{{ $errors->updatePassword->first('current_password') }}</p>
                                    @endif
                                </div>

                                <div class="grid gap-5 md:grid-cols-2">
                                    <div>
                                        <label for="password" class="mb-2 block text-sm font-bold text-slate-700">New password</label>
                                        <input id="password" name="password" type="password" autocomplete="new-password" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3.5 text-slate-900 shadow-sm outline-none transition focus:border-[#C1272D] focus:bg-white focus:ring-4 focus:ring-red-100">
                                        @if ($errors->updatePassword->has('password'))
                                            <p class="mt-2 text-sm font-medium text-red-600">{{ $errors->updatePassword->first('password') }}</p>
                                        @endif
                                    </div>

                                    <div>
                                        <label for="password_confirmation" class="mb-2 block text-sm font-bold text-slate-700">Confirm password</label>
                                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3.5 text-slate-900 shadow-sm outline-none transition focus:border-[#C1272D] focus:bg-white focus:ring-4 focus:ring-red-100">
                                        @if ($errors->updatePassword->has('password_confirmation'))
                                            <p class="mt-2 text-sm font-medium text-red-600">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                                        @endif
                                    </div>
                                </div>

                                <button type="submit" class="inline-flex items-center rounded-xl bg-[#C1272D] px-5 py-3 text-sm font-extrabold text-white transition hover:bg-[#a81f25]">
                                    Update Password
                                </button>
                            </form>
                        </section>
                    </div>

                    <div class="space-y-6">
                        <section class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm" style="padding: 30px;">
                            <div class="border-b border-gray-100 pb-5">
                                <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#C1272D]">Volunteer Summary</p>
                                <h2 class="mt-2 text-2xl font-extrabold text-slate-900">Account Snapshot</h2>
                            </div>

                            <div class="mt-6 grid gap-4">
                                <div class="rounded-xl border border-gray-200 bg-gray-50 px-5 py-4">
                                    <p class="text-[10px] font-bold uppercase tracking-[0.22em] text-slate-400">Volunteer Badge</p>
                                    <p class="mt-2 text-base font-bold text-slate-900">{{ $volunteer->name }}</p>
                                </div>

                                <div class="rounded-xl border border-gray-200 bg-gray-50 px-5 py-4">
                                    <p class="text-[10px] font-bold uppercase tracking-[0.22em] text-slate-400">Availability</p>
                                    <p class="mt-2 text-base font-bold text-slate-900">{{ $availabilityList->take(2)->implode(', ') ?: 'Not set' }}</p>
                                </div>

                                <div class="rounded-xl border border-gray-200 bg-gray-50 px-5 py-4">
                                    <p class="text-[10px] font-bold uppercase tracking-[0.22em] text-slate-400">Languages</p>
                                    <p class="mt-2 text-base font-bold text-slate-900">{{ $languageList->take(3)->implode(', ') ?: 'Not set' }}</p>
                                </div>
                            </div>
                        </section>

                        @if ($skillList->isNotEmpty() || $languageList->isNotEmpty() || $availabilityList->isNotEmpty())
                            <section class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm" style="padding: 30px;">
                                <div class="border-b border-gray-100 pb-5">
                                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#C1272D]">Capabilities</p>
                                    <h2 class="mt-2 text-2xl font-extrabold text-slate-900">Availability, Skills and Languages</h2>
                                </div>

                                <div class="mt-6 space-y-5">
                                    @if ($availabilityList->isNotEmpty())
                                        <div>
                                            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">Availability</p>
                                            <div class="mt-3 flex flex-wrap gap-3">
                                                @foreach ($availabilityList as $availability)
                                                    <span class="inline-flex rounded-full bg-emerald-50 px-4 py-2 text-xs font-bold uppercase tracking-wider text-emerald-700">
                                                        {{ $availability }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    @if ($skillList->isNotEmpty())
                                        <div>
                                            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">Skills</p>
                                            <div class="mt-3 flex flex-wrap gap-3">
                                                @foreach ($skillList as $skill)
                                                    <span class="inline-flex rounded-full bg-red-50 px-4 py-2 text-xs font-bold uppercase tracking-wider text-[#C1272D]">
                                                        {{ $skill }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    @if ($languageList->isNotEmpty())
                                        <div>
                                            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">Languages</p>
                                            <div class="mt-3 flex flex-wrap gap-3">
                                                @foreach ($languageList as $language)
                                                    <span class="inline-flex rounded-full bg-slate-100 px-4 py-2 text-xs font-bold uppercase tracking-wider text-slate-700">
                                                        {{ $language }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </section>
                        @endif

                        <section class="rounded-2xl border border-red-200 bg-white p-8 shadow-sm" style="padding: 30px;">
                            <div class="border-b border-red-100 pb-5">
                                <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#C1272D]">Danger Zone</p>
                                <h2 class="mt-2 text-2xl font-extrabold text-slate-900">Delete Account</h2>
                                <p class="mt-2 text-sm font-medium text-slate-600">This permanently removes your volunteer access and account data from the platform.</p>
                            </div>

                            <form method="POST" action="{{ route('profile.destroy') }}" class="mt-6 space-y-4">
                                @csrf
                                @method('DELETE')

                                <div>
                                    <label for="delete_password" class="mb-2 block text-sm font-bold text-slate-700">Current password</label>
                                    <input id="delete_password" name="password" type="password" autocomplete="current-password" class="w-full rounded-xl border border-red-200 bg-red-50/40 px-4 py-3.5 text-slate-900 shadow-sm outline-none transition focus:border-[#C1272D] focus:bg-white focus:ring-4 focus:ring-red-100">
                                    @if ($errors->userDeletion->has('password'))
                                        <p class="mt-2 text-sm font-medium text-red-600">{{ $errors->userDeletion->first('password') }}</p>
                                    @endif
                                </div>

                                <button type="submit" onclick="return confirm('Delete your account permanently?')" class="inline-flex items-center rounded-xl bg-[#C1272D] px-5 py-3 text-sm font-extrabold text-white transition hover:bg-[#a81f25]">
                                    Delete Account
                                </button>
                            </form>
                        </section>
                    </div>
                </div>

                <footer class="mt-10 border-t border-gray-200 py-6 text-center text-xs font-bold text-slate-400">
                    &copy; {{ date('Y') }} Volunlink - Morocco 2030 World Cup. All rights reserved.
                </footer>
            </div>
        </main>
    </div>
</div>
@endsection
