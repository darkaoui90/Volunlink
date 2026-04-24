@props([
    'context' => 'login',
])

@php
    $isRegister = $context === 'register';

    $heading = $isRegister
        ? 'Join the volunteer force shaping Morocco 2030.'
        : 'Operate every mission with confidence and precision.';

    $description = $isRegister
        ? 'Build your volunteer profile, share your skills, and become part of a coordinated national impact network.'
        : 'Sign in to coordinate missions, track QR attendance, and lead teams across cities in real time.';
@endphp

<aside class="relative overflow-hidden rounded-[2rem] border border-white/20 bg-white/10 p-6 shadow-[0_22px_55px_rgba(0,0,0,0.24)] backdrop-blur-xl lg:p-10">
    <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(155deg,rgba(16,185,129,0.2)_0%,rgba(15,23,42,0.18)_45%,rgba(220,38,38,0.22)_100%)]"></div>

    <div class="relative flex h-full flex-col">
        <div class="inline-flex items-center gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-red-500 text-lg font-bold text-white shadow-lg">
                V
            </div>
            <div>
                <p class="text-[10px] uppercase tracking-[0.3em] text-emerald-100/85">Smart Volunteer Platform</p>
                <p class="text-lg font-semibold text-white" style="font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;">Volunlink Morocco 2030</p>
            </div>
        </div>

        <h1 class="mt-8 text-3xl font-semibold leading-tight text-white sm:text-4xl" style="font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;">
            {{ $heading }}
        </h1>
        <p class="mt-4 max-w-xl text-sm leading-relaxed text-emerald-50/90 sm:text-base">
            {{ $description }}
        </p>

        <div class="mt-8 grid gap-4 sm:grid-cols-2">
            <article class="rounded-2xl border border-white/20 bg-white/10 p-4">
                <p class="text-xs uppercase tracking-wide text-emerald-100/90">Cities Coordinated</p>
                <p class="mt-3 text-2xl font-semibold text-white">12+</p>
                <p class="mt-1 text-sm text-emerald-50/80">Casablanca, Rabat, Tanger, Fes and growing.</p>
            </article>

            <article class="rounded-2xl border border-white/20 bg-white/10 p-4">
                <p class="text-xs uppercase tracking-wide text-emerald-100/90">Attendance Control</p>
                <p class="mt-3 text-2xl font-semibold text-white">QR Live</p>
                <p class="mt-1 text-sm text-emerald-50/80">Fast check-ins and reliable mission traceability.</p>
            </article>
        </div>

        <div class="mt-6 rounded-2xl border border-white/20 bg-slate-950/30 p-4">
            <p class="text-xs uppercase tracking-wide text-emerald-100/85">Operational Snapshot</p>

            <div class="mt-4 space-y-3 text-sm">
                <div class="flex items-center justify-between rounded-xl bg-white/10 px-3 py-2">
                    <span class="text-emerald-50/90">Mission Coverage</span>
                    <span class="font-semibold text-white">98%</span>
                </div>
                <div class="flex items-center justify-between rounded-xl bg-white/10 px-3 py-2">
                    <span class="text-emerald-50/90">Team Collaboration Index</span>
                    <span class="font-semibold text-white">High</span>
                </div>
                <div class="flex items-center justify-between rounded-xl bg-white/10 px-3 py-2">
                    <span class="text-emerald-50/90">Impact Reports</span>
                    <span class="font-semibold text-white">Daily</span>
                </div>
            </div>
        </div>

        <div class="mt-8 flex flex-wrap gap-3 text-xs text-emerald-50/90">
            <span class="rounded-full border border-emerald-200/40 bg-emerald-400/15 px-3 py-1">Volunteer Operations</span>
            <span class="rounded-full border border-red-200/40 bg-red-400/15 px-3 py-1">Event Coordination</span>
            <span class="rounded-full border border-white/30 bg-white/10 px-3 py-1">Morocco 2030 Vision</span>
        </div>

        @if ($isRegister && Route::has('login'))
            <a href="{{ route('login') }}" class="mt-7 inline-flex items-center gap-2 text-sm font-medium text-emerald-100 transition hover:text-white">
                Already registered? Sign in
                <span aria-hidden="true">-></span>
            </a>
        @elseif (!$isRegister && Route::has('register'))
            <a href="{{ route('register') }}" class="mt-7 inline-flex items-center gap-2 text-sm font-medium text-emerald-100 transition hover:text-white">
                New volunteer? Create account
                <span aria-hidden="true">-></span>
            </a>
        @endif
    </div>
</aside>
