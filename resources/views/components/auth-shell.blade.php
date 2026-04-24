@props([
    'context' => 'login',
    'pageTitle' => 'Volunlink Authentication',
])

@php
    $isRegister = $context === 'register';
    $backgroundImage = asset('auth/world-cup-2030-theme.png');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700,800|plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .auth-brand-word {
            font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
            letter-spacing: -0.05em;
        }

        .auth-brand-mark {
            position: relative;
            width: 66px;
            height: 48px;
        }

        .auth-brand-mark span {
            position: absolute;
            display: block;
            border-radius: 9999px;
        }

        .auth-brand-mark .auth-gold-left {
            left: 6px;
            top: 2px;
            width: 15px;
            height: 42px;
            transform: rotate(-31deg);
            background: linear-gradient(180deg, #ffbf4b 0%, #ff9120 100%);
        }

        .auth-brand-mark .auth-coral-right {
            left: 28px;
            top: 4px;
            width: 16px;
            height: 40px;
            transform: rotate(30deg);
            background: linear-gradient(180deg, #ff7f5b 0%, #d84d3f 100%);
        }

        .auth-brand-mark .auth-teal-base {
            left: 15px;
            top: 20px;
            width: 35px;
            height: 13px;
            border-radius: 9999px 9999px 18px 18px;
            background: linear-gradient(90deg, #47a7d8 0%, #53c89f 100%);
        }

        .auth-brand-mark .auth-sky-dot {
            right: 4px;
            top: 1px;
            width: 12px;
            height: 12px;
            background: #5ca8e5;
        }
    </style>
</head>
<body class="min-h-screen bg-white antialiased text-slate-900" style="font-family: 'Plus Jakarta Sans', 'Outfit', sans-serif;">
    <div class="min-h-screen">
        <div class="grid min-h-screen lg:grid-cols-[minmax(0,705px)_1fr]">
            <main class="flex min-h-screen items-center justify-center bg-white px-4 py-8 sm:px-8 lg:px-10 xl:px-12">
                <section class="w-full max-w-[620px]">
                    <div class="mx-auto flex max-w-md flex-col items-center text-center">
                        <a href="{{ url('/') }}" class="flex flex-col items-center">
                            <div class="flex items-center gap-3">
                                <div class="auth-brand-mark" aria-hidden="true">
                                    <span class="auth-gold-left"></span>
                                    <span class="auth-coral-right"></span>
                                    <span class="auth-teal-base"></span>
                                    <span class="auth-sky-dot"></span>
                                </div>
                                <span class="auth-brand-word text-[2.85rem] font-bold leading-none text-slate-900">Volunlink</span>
                            </div>
                            <p class="mt-2 text-sm font-medium leading-6 text-slate-700">
                                Smart Volunteer Management Platform
                                <br>
                                of the 2030 FIFA World Cup
                            </p>
                        </a>
                    </div>

                    <div class="mt-8 overflow-hidden rounded-[22px] border border-slate-300 bg-white shadow-[0_18px_45px_rgba(15,23,42,0.08)]">
                        <div class="grid grid-cols-2 border-b border-slate-200 bg-slate-50/60 text-center text-[1.05rem] font-semibold text-slate-500">
                            @if (Route::has('login'))
                                <a
                                    href="{{ route('login') }}"
                                    class="px-5 py-4 {{ $isRegister ? 'text-slate-500' : 'border-b-2 border-slate-900 bg-white text-slate-900' }}"
                                >
                                    Sign In
                                </a>
                            @else
                                <span class="px-5 py-4">Sign In</span>
                            @endif

                            @if (Route::has('register'))
                                <a
                                    href="{{ route('register') }}"
                                    class="px-5 py-4 {{ $isRegister ? 'border-b-2 border-slate-900 bg-white text-slate-900' : 'text-slate-500' }}"
                                >
                                    Register
                                </a>
                            @else
                                <span class="px-5 py-4">Register</span>
                            @endif
                        </div>

                        <div class="px-6 py-7 sm:px-8 sm:py-8">
                            {{ $slot }}
                        </div>
                    </div>
                </section>
            </main>

            <aside
                class="relative hidden min-h-screen overflow-hidden lg:block"
                style="background-image: url('{{ $backgroundImage }}'); background-size: cover; background-position: center;"
            >
                <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(255,255,255,0.02),rgba(0,0,0,0.02))]"></div>
            </aside>
        </div>
    </div>
</body>
</html>
