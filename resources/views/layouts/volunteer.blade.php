<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Volunteer Dashboard') — Volunlink</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Poppins', sans-serif; }
        h1,h2,h3 { font-family: 'Playfair Display', serif; }

        :root {
            --red:   #ce1126;
            --green: #007a5e;
            --gold:  #d4af37;
            --navy:  #1e3a8a;
        }

        /* ── Sidebar ── */
        #sidebar {
            width: 280px;
            transition: width .3s ease, transform .3s ease;
            background-color: #0f172a; /* Slate 900 */
        }
        #sidebar.collapsed { width: 80px; }
        #sidebar.collapsed .nav-label,
        #sidebar.collapsed .brand-sub,
        #sidebar.collapsed .profile-info { display: none; }
        #sidebar.collapsed .nav-item { justify-content: center; padding-left: 0; padding-right: 0; }
        #sidebar.collapsed .brand-logo { margin: 0 auto; }
        
        /* Sidebar Navigation Items */
        .nav-item {
            position: relative;
            transition: all 0.2s ease;
        }
        .nav-item:hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: white;
        }
        .nav-item.active {
            background: linear-gradient(90deg, rgba(206,17,38,0.15) 0%, transparent 100%);
            color: white;
        }
        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background-color: #ce1126;
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
        }

        /* Mobile overlay */
        @media (max-width: 1023px) {
            #sidebar { position: fixed; top: 0; left: 0; height: 100vh; z-index: 50; transform: translateX(-100%); width: 280px !important; }
            #sidebar.mobile-open { transform: translateX(0); }
            #sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 40; backdrop-filter: blur(2px); }
            #sidebar-overlay.active { display: block; }
        }

        /* ── Stat cards ── */
        .stat-card { transition: transform .2s, box-shadow .2s; }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,.08); }

        /* ── Mission cards ── */
        .mission-card { transition: transform .25s, box-shadow .25s; }
        .mission-card:hover { transform: translateY(-6px); box-shadow: 0 24px 48px rgba(0,0,0,.1); }

        /* ── Progress bar animation ── */
        .progress-fill { transition: width 1.2s ease; }

        /* ── Count-up ── */
        .count-up { display: inline-block; }

        /* ── Custom Scrollbar for Main Content ── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        /* ── Custom Scrollbar for Sidebar ── */
        #sidebar-nav::-webkit-scrollbar { width: 4px; }
        #sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
        #sidebar-nav::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }

        /* ── Bottom mobile nav ── */
        #mobile-bottom-nav { display: none; }
        @media (max-width: 1023px) { #mobile-bottom-nav { display: flex; } }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased overflow-hidden">

{{-- Mobile overlay --}}
<div id="sidebar-overlay" onclick="closeSidebar()" class="transition-opacity duration-300"></div>

<!-- Outer Wrapper: Full Viewport Height -->
<div class="flex h-screen w-full overflow-hidden">

    {{-- ══════════════ SIDEBAR ══════════════ --}}
    <!-- Fixed full height sidebar -->
    <aside id="sidebar" class="flex flex-col flex-shrink-0 h-full border-r border-slate-800 shadow-xl z-20">

        {{-- Brand / Header --}}
        <div class="flex items-center gap-4 px-6 py-6 border-b border-white/5 bg-slate-900/50">
            <div class="brand-logo w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-red-900/20"
                 style="background:linear-gradient(135deg,#ce1126,#990c1b)">
                <span class="text-white font-bold text-xl" style="font-family:'Playfair Display',serif">V</span>
            </div>
            <div class="nav-label flex flex-col">
                <span class="text-white font-bold text-lg tracking-wide leading-tight" style="font-family:'Playfair Display',serif">Volunlink</span>
                <span class="brand-sub text-[11px] uppercase tracking-wider font-semibold text-amber-400/90 mt-0.5">Morocco 2030</span>
            </div>
        </div>

        {{-- Scrollable Navigation Area --}}
        <nav id="sidebar-nav" class="flex-1 overflow-y-auto py-6 px-4 space-y-2">

            <div class="nav-label px-3 mb-2 text-[10px] uppercase tracking-wider font-semibold text-slate-500">Main Menu</div>

            <a href="{{ route('volunteer.dashboard') }}"
               class="nav-item {{ request()->routeIs('volunteer.dashboard') ? 'active' : 'text-slate-400' }} flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium">
                <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('volunteer.dashboard') ? 'text-red-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="nav-label">Dashboard</span>
            </a>

            <a href="#missions-section"
               class="nav-item text-slate-400 flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span class="nav-label">My Missions</span>
            </a>

            <a href="{{ route('profile.edit') }}"
               class="nav-item {{ request()->routeIs('profile.edit') ? 'active' : 'text-slate-400' }} flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium">
                <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('profile.edit') ? 'text-red-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="nav-label">Profile settings</span>
            </a>

            <a href="#history-section"
               class="nav-item text-slate-400 flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="nav-label">History & Log</span>
            </a>

            <div class="nav-label px-3 mt-8 mb-2 text-[10px] uppercase tracking-wider font-semibold text-slate-500">System</div>

            <a href="#"
               class="nav-item text-slate-400 flex items-center justify-between px-4 py-3 rounded-lg text-sm font-medium">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="nav-label">Notifications</span>
                </div>
                <span class="nav-label bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm">3</span>
            </a>
        </nav>

        {{-- Collapse toggle (desktop) --}}
        <div class="px-4 pb-2 pt-2 border-t border-white/5 hidden lg:block">
            <button onclick="toggleSidebar()"
                    class="nav-item w-full flex items-center gap-3 px-4 py-3 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 text-sm font-medium transition-colors">
                <svg id="collapse-icon" class="w-5 h-5 flex-shrink-0 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                </svg>
                <span class="nav-label">Collapse Menu</span>
            </button>
        </div>

        {{-- Profile card / Footer --}}
        <div class="border-t border-white/10 p-5 bg-slate-900/80">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 text-white font-bold text-sm shadow-md"
                     style="background:linear-gradient(135deg,#ce1126,#007a5e)">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="profile-info min-w-0 flex-1">
                    <p class="text-white text-sm font-semibold truncate">{{ auth()->user()->name }}</p>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <div class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></div>
                        <span class="text-xs text-slate-400 font-medium">Volunteer</span>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="profile-info flex-shrink-0">
                    @csrf
                    <button type="submit" title="Logout"
                            class="p-2 text-slate-400 hover:text-red-400 hover:bg-red-400/10 rounded-lg transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ══════════════ MAIN SCROLLABLE CONTENT ══════════════ --}}
    <!-- This div takes the remaining width and is independently scrollable -->
    <div id="main-content" class="flex-1 flex flex-col min-w-0 h-full overflow-y-auto bg-slate-50 relative">

        {{-- Mobile top bar --}}
        <header class="lg:hidden sticky top-0 z-30 flex items-center justify-between px-5 py-4 bg-white/80 backdrop-blur-md border-b border-slate-200 shadow-sm">
            <button onclick="openSidebar()" class="p-2 -ml-2 rounded-lg text-slate-600 hover:bg-slate-100 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <span class="font-bold text-slate-800 text-lg tracking-wide" style="font-family:'Playfair Display',serif">Volunlink</span>
            <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-sm"
                 style="background:linear-gradient(135deg,#ce1126,#007a5e)">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        </header>

        <main class="flex-1 pb-24 lg:pb-12">
            @yield('content')
        </main>
    </div>
</div>

{{-- Mobile bottom nav --}}
<nav id="mobile-bottom-nav" class="lg:hidden fixed bottom-0 left-0 right-0 z-30 bg-white/90 backdrop-blur-md border-t border-slate-200 items-center justify-around px-2 py-3 shadow-[0_-4px_15px_rgba(0,0,0,0.05)]">
    <a href="{{ route('volunteer.dashboard') }}" class="flex flex-col items-center gap-1 text-[11px] font-semibold transition-colors {{ request()->routeIs('volunteer.dashboard') ? 'text-[#ce1126]' : 'text-slate-500' }}">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Home
    </a>
    <a href="#missions-section" class="flex flex-col items-center gap-1 text-[11px] font-semibold text-slate-500 transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        Missions
    </a>
    <a href="{{ route('profile.edit') }}" class="flex flex-col items-center gap-1 text-[11px] font-semibold transition-colors {{ request()->routeIs('profile.edit') ? 'text-[#ce1126]' : 'text-slate-500' }}">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
        Profile
    </a>
    <a href="#" class="flex flex-col items-center gap-1 text-[11px] font-semibold text-slate-500 transition-colors relative">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        <span class="absolute -top-1 right-2 w-4 h-4 bg-red-500 border-2 border-white rounded-full text-white text-[9px] flex items-center justify-center">3</span>
        Alerts
    </a>
</nav>

<script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    let collapsed = false;

    function toggleSidebar() {
        collapsed = !collapsed;
        sidebar.classList.toggle('collapsed', collapsed);
        document.getElementById('collapse-icon').style.transform = collapsed ? 'rotate(180deg)' : '';
    }
    function openSidebar() {
        sidebar.classList.add('mobile-open');
        overlay.classList.add('active');
    }
    function closeSidebar() {
        sidebar.classList.remove('mobile-open');
        overlay.classList.remove('active');
    }

    // Count-up animation
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.count-up').forEach(el => {
            const target = parseInt(el.dataset.target, 10);
            if (isNaN(target)) return;
            let current = 0;
            const step = Math.ceil(target / 40);
            const timer = setInterval(() => {
                current = Math.min(current + step, target);
                el.textContent = current;
                if (current >= target) clearInterval(timer);
            }, 30);
        });

        // Animate progress bars
        setTimeout(() => {
            document.querySelectorAll('.progress-fill').forEach(bar => {
                bar.style.width = bar.dataset.width;
            });
        }, 300);
    });
</script>
</body>
</html>
