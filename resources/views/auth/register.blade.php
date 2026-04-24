<x-auth-shell context="register" page-title="Register | Volunlink">
    @php
        $inputClass = 'mt-1 block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-slate-900 focus:ring-2 focus:ring-slate-200';
        $textareaClass = 'mt-1 block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-slate-900 focus:ring-2 focus:ring-slate-200';
    @endphp

    <div>
        <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Volunteer Onboarding</p>
        <h2 class="mt-2 text-2xl font-semibold text-slate-900" style="font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;">Create your account</h2>
        <p class="mt-2 text-sm leading-6 text-slate-600">Complete the same registration fields below.</p>
    </div>

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

    <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
        @csrf

        <div class="grid gap-5 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label for="name" class="text-sm font-semibold text-slate-700">Full name</label>
                <input id="name" class="{{ $inputClass }}" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="text-sm font-semibold text-slate-700">Email address</label>
                <input id="email" class="{{ $inputClass }}" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
                @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="phone" class="text-sm font-semibold text-slate-700">Phone number</label>
                <input id="phone" class="{{ $inputClass }}" type="text" name="phone" value="{{ old('phone') }}" required autocomplete="tel" placeholder="+212 ...">
                @error('phone')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="city" class="text-sm font-semibold text-slate-700">City</label>
                <input id="city" class="{{ $inputClass }}" type="text" name="city" value="{{ old('city') }}" required placeholder="Casablanca">
                @error('city')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="availability" class="text-sm font-semibold text-slate-700">Availability</label>
                <select id="availability" name="availability" class="{{ $inputClass }}" required>
                    <option value="" @selected(old('availability') === null)>Select availability</option>
                    <option value="weekdays" @selected(old('availability') === 'weekdays')>Weekdays</option>
                    <option value="weekends" @selected(old('availability') === 'weekends')>Weekends</option>
                    <option value="full-time" @selected(old('availability') === 'full-time')>Full time</option>
                    <option value="on-demand" @selected(old('availability') === 'on-demand')>On demand</option>
                </select>
                @error('availability')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="languages" class="text-sm font-semibold text-slate-700">Languages</label>
                <textarea id="languages" name="languages" rows="2" class="{{ $textareaClass }}" required placeholder="Arabic, French, English...">{{ old('languages') }}</textarea>
                @error('languages')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="skills" class="text-sm font-semibold text-slate-700">Skills</label>
                <textarea id="skills" name="skills" rows="2" class="{{ $textareaClass }}" required placeholder="Crowd guidance, first aid, logistics...">{{ old('skills') }}</textarea>
                @error('skills')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="text-sm font-semibold text-slate-700">Password</label>
                <input id="password" class="{{ $inputClass }}" type="password" name="password" required autocomplete="new-password">
                @error('password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="text-sm font-semibold text-slate-700">Confirm password</label>
                <input id="password_confirmation" class="{{ $inputClass }}" type="password" name="password_confirmation" required autocomplete="new-password">
            </div>
        </div>

        <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-[#243447] px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/15 transition hover:bg-[#162033]">
            Create account
        </button>

        <p class="text-center text-sm text-slate-600">
            Already have access?
            @if (Route::has('login'))
                <a href="{{ route('login') }}" class="font-semibold text-slate-900 hover:text-[#243447]">Sign in here</a>
            @else
                <span class="font-semibold text-slate-500">Login unavailable</span>
            @endif
        </p>
    </form>
</x-auth-shell>
