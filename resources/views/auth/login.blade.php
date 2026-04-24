<x-auth-shell context="login" page-title="Sign In | Volunlink">
    @php
        $inputClass = 'mt-1 block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-slate-900 focus:ring-2 focus:ring-slate-200';
    @endphp

    <div class="lg:pb-28">
        <div>
            <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Secure Access</p>
            <h2 class="mt-2 text-2xl font-semibold text-slate-900" style="font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;">Welcome back</h2>
            <p class="mt-2 text-sm leading-6 text-slate-600">Sign in with your existing credentials.</p>
        </div>

        @if (session('status'))
            <div class="mt-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <p class="font-semibold">Please fix the following:</p>
                <ul class="mt-2 list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
            @csrf

            <div>
                <label for="email" class="text-sm font-semibold text-slate-700">Email address</label>
                <input id="email" class="{{ $inputClass }}" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="text-sm font-semibold text-slate-700">Password</label>
                <input id="password" class="{{ $inputClass }}" type="password" name="password" required autocomplete="current-password">
                @error('password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between gap-3">
                <label for="remember_me" class="inline-flex items-center gap-2 text-sm text-slate-600">
                    <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-900">
                    <span>Remember me</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm font-medium text-slate-700 transition hover:text-slate-900" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
            </div>

            <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-[#243447] px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/15 transition hover:bg-[#162033]">
                Sign in to platform
            </button>

            <p class="text-center text-sm text-slate-600">
                Need an account?
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="font-semibold text-slate-900 hover:text-[#243447]">Register as volunteer</a>
                @else
                    <span class="font-semibold text-slate-500">Registration unavailable</span>
                @endif
            </p>
        </form>
    </div>
</x-auth-shell>
