@extends('layouts.admin')
@section('title', 'Profile')

@section('content')
@php
    $initials = collect(preg_split('/\s+/', trim($user->name)))
        ->filter()
        ->take(2)
        ->map(fn (string $part) => strtoupper(substr($part, 0, 1)))
        ->implode('');

    $emailStatusLabel = $user->email_verified_at ? 'Verified' : 'Pending verification';
    $emailStatusClass = $user->email_verified_at
        ? 'bg-emerald-50 text-emerald-700'
        : 'bg-amber-50 text-amber-700';
@endphp

<div class="mx-auto max-w-6xl space-y-6">
    <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h1 class="text-3xl font-semibold text-gray-900">Profile</h1>
                <p class="mt-2 text-sm text-gray-500">
                    Manage your personal information and account security.
                </p>
            </div>

            <a href="{{ route($user->dashboardRouteName()) }}" class="inline-flex items-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50">
                Back to Dashboard
            </a>
        </div>
    </section>

    <div class="grid gap-6 lg:grid-cols-[320px_1fr]">
        <aside class="space-y-6">
            <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-red-100 text-2xl font-semibold text-[#C1272D]">
                        {{ $initials }}
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">{{ $user->name }}</h2>
                        <p class="mt-1 text-sm text-gray-500">{{ ucfirst($user->normalizedRole()) }}</p>
                    </div>
                </div>

                <dl class="mt-6 space-y-4 text-sm">
                    <div>
                        <dt class="text-gray-400">Email</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ $user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400">Status</dt>
                        <dd class="mt-2">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $emailStatusClass }}">
                                {{ $emailStatusLabel }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-400">Phone</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ $user->phone ?: 'Not set' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400">City</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ $user->city ?: 'Not set' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400">Member since</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ $user->created_at?->format('F Y') }}</dd>
                    </div>
                </dl>
            </section>
        </aside>

        <div class="space-y-6">
            <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Personal Information</h2>
                        <p class="mt-1 text-sm text-gray-500">Update the details attached to your admin account.</p>
                    </div>

                    @if (session('status') === 'profile-updated')
                        <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                            Saved
                        </span>
                    @endif
                </div>

                <form method="POST" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                    @csrf
                    @method('PATCH')

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label for="name" class="mb-2 block text-sm font-medium text-gray-700">Full name</label>
                            <input id="name" name="name" type="text" required autofocus autocomplete="name" value="{{ old('name', $user->name) }}" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-[#C1272D] focus:outline-none focus:ring-2 focus:ring-[#C1272D]/20">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="mb-2 block text-sm font-medium text-gray-700">Email address</label>
                            <input id="email" name="email" type="email" required autocomplete="username" value="{{ old('email', $user->email) }}" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-[#C1272D] focus:outline-none focus:ring-2 focus:ring-[#C1272D]/20">
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="mb-2 block text-sm font-medium text-gray-700">Phone</label>
                            <input id="phone" name="phone" type="text" autocomplete="tel" value="{{ old('phone', $user->phone) }}" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-[#C1272D] focus:outline-none focus:ring-2 focus:ring-[#C1272D]/20">
                            @error('phone')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="city" class="mb-2 block text-sm font-medium text-gray-700">City</label>
                            <input id="city" name="city" type="text" autocomplete="address-level2" value="{{ old('city', $user->city) }}" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-[#C1272D] focus:outline-none focus:ring-2 focus:ring-[#C1272D]/20">
                            @error('city')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="inline-flex items-center rounded-lg bg-[#C1272D] px-5 py-3 text-sm font-semibold text-white transition-colors hover:bg-[#a81f25]">
                        Save Changes
                    </button>
                </form>
            </section>

            <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Update Password</h2>
                        <p class="mt-1 text-sm text-gray-500">Choose a strong password for your admin account.</p>
                    </div>

                    @if (session('status') === 'password-updated')
                        <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                            Password updated
                        </span>
                    @endif
                </div>

                <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="mb-2 block text-sm font-medium text-gray-700">Current password</label>
                        <input id="current_password" name="current_password" type="password" autocomplete="current-password" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-[#C1272D] focus:outline-none focus:ring-2 focus:ring-[#C1272D]/20">
                        @if ($errors->updatePassword->has('current_password'))
                            <p class="mt-2 text-sm text-red-600">{{ $errors->updatePassword->first('current_password') }}</p>
                        @endif
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label for="password" class="mb-2 block text-sm font-medium text-gray-700">New password</label>
                            <input id="password" name="password" type="password" autocomplete="new-password" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-[#C1272D] focus:outline-none focus:ring-2 focus:ring-[#C1272D]/20">
                            @if ($errors->updatePassword->has('password'))
                                <p class="mt-2 text-sm text-red-600">{{ $errors->updatePassword->first('password') }}</p>
                            @endif
                        </div>

                        <div>
                            <label for="password_confirmation" class="mb-2 block text-sm font-medium text-gray-700">Confirm password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-[#C1272D] focus:outline-none focus:ring-2 focus:ring-[#C1272D]/20">
                            @if ($errors->updatePassword->has('password_confirmation'))
                                <p class="mt-2 text-sm text-red-600">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                            @endif
                        </div>
                    </div>

                    <button type="submit" class="inline-flex items-center rounded-lg border border-gray-900 px-5 py-3 text-sm font-semibold text-gray-900 transition-colors hover:bg-gray-50">
                        Update Password
                    </button>
                </form>
            </section>

            <section class="rounded-2xl border border-red-200 bg-red-50/60 p-6 shadow-sm">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Delete Account</h2>
                    <p class="mt-1 text-sm text-gray-600">
                        Permanently remove your account after confirming with your current password.
                    </p>
                </div>

                <form method="POST" action="{{ route('profile.destroy') }}" class="mt-6 grid gap-4 md:grid-cols-[1fr_auto] md:items-end">
                    @csrf
                    @method('DELETE')

                    <div>
                        <label for="delete_password" class="mb-2 block text-sm font-medium text-gray-700">Current password</label>
                        <input id="delete_password" name="password" type="password" autocomplete="current-password" class="w-full rounded-xl border border-red-200 bg-white px-4 py-3 text-gray-900 focus:border-[#C1272D] focus:outline-none focus:ring-2 focus:ring-[#C1272D]/20">
                        @if ($errors->userDeletion->has('password'))
                            <p class="mt-2 text-sm text-red-600">{{ $errors->userDeletion->first('password') }}</p>
                        @endif
                    </div>

                    <button type="submit" onclick="return confirm('Delete your account permanently?')" class="inline-flex items-center justify-center rounded-lg bg-[#C1272D] px-5 py-3 text-sm font-semibold text-white transition-colors hover:bg-[#a81f25]">
                        Delete Account
                    </button>
                </form>
            </section>
        </div>
    </div>
</div>
@endsection
