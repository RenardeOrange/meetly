<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Meetly - @yield('title', __('app.home'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-gradient: linear-gradient(135deg, #f97316, #ef4444, #ec4899);
            --navbar-bg: rgba(255, 255, 255, 0.12);
            --navbar-border: rgba(255, 255, 255, 0.15);
            --card-bg: rgba(255, 255, 255, 0.12);
            --card-border: rgba(255, 255, 255, 0.2);
            --text-primary: #fff;
            --text-muted: rgba(255, 255, 255, 0.74);
            --input-bg: rgba(255, 255, 255, 0.12);
            --input-border: rgba(255, 255, 255, 0.24);
            --mobile-nav-bg: rgba(44, 62, 80, 0.95);
            --dropdown-bg: rgba(30, 15, 35, 0.97);
            --dropdown-border: rgba(255, 255, 255, 0.15);
        }

        body.dark-mode {
            --bg-gradient: linear-gradient(135deg, #0f0f1a, #1a1a2e, #16213e);
            --navbar-bg: rgba(0, 0, 0, 0.4);
            --navbar-border: rgba(255, 255, 255, 0.08);
            --card-bg: rgba(255, 255, 255, 0.06);
            --card-border: rgba(255, 255, 255, 0.1);
            --text-primary: #e8e8f0;
            --text-muted: rgba(220, 220, 240, 0.65);
            --input-bg: rgba(255, 255, 255, 0.07);
            --input-border: rgba(255, 255, 255, 0.14);
            --mobile-nav-bg: rgba(10, 10, 20, 0.97);
            --dropdown-bg: rgba(10, 10, 20, 0.98);
            --dropdown-border: rgba(255, 255, 255, 0.08);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            background: var(--bg-gradient);
            background-size: 200% 200%;
            animation: gradientShift 8s ease infinite;
            display: flex;
            flex-direction: column;
            color: var(--text-primary);
        }
        body.dark-mode { animation: none; }
        @keyframes gradientShift { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }

        /* ── Navbar base ── */
        .navbar { display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 2rem; background: var(--navbar-bg); backdrop-filter: blur(20px); border-bottom: 1px solid var(--navbar-border); position: sticky; top: 0; z-index: 200; }
        .navbar-brand { font-size: 1.5rem; font-weight: 700; color: var(--text-primary); text-decoration: none; letter-spacing: 2px; text-transform: uppercase; }
        .navbar-nav { display: flex; gap: 0.25rem; list-style: none; align-items: center; }

        /* ── Standard nav link ── */
        .nav-link { display: flex; align-items: center; gap: 0.4rem; padding: 0.6rem 1.1rem; color: rgba(255, 255, 255, 0.8); text-decoration: none; border-radius: 50px; font-size: 0.88rem; font-weight: 500; transition: all 0.3s ease; white-space: nowrap; }
        .nav-link:hover, .nav-link.active { background: rgba(255, 255, 255, 0.2); color: #fff; }
        .nav-link svg, .mobile-nav-link svg { width: 20px; height: 20px; fill: currentColor; flex-shrink: 0; }

        /* ── Badge ── */
        .nav-badge { display: inline-flex; align-items: center; justify-content: center; background: #e74c3c; color: #fff; border-radius: 999px; min-width: 18px; height: 18px; font-size: 0.65rem; font-weight: 700; padding: 0 4px; margin-left: 2px; line-height: 1; }
        .nav-link-wrap { position: relative; display: flex; align-items: center; gap: 0.4rem; }

        /* ── Dropdown group ── */
        .nav-dropdown-group,
        .navbar-user { position: relative; list-style: none; }
        .nav-dropdown-group::after,
        .navbar-user::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            height: 0.95rem;
        }

        .nav-dropdown-trigger {
            display: flex; align-items: center; gap: 0.4rem;
            padding: 0.6rem 1.1rem;
            color: rgba(255, 255, 255, 0.8);
            border-radius: 50px;
            font-size: 0.88rem; font-weight: 500;
            cursor: pointer;
            background: none; border: none; font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
            white-space: nowrap;
        }
        .nav-dropdown-trigger svg { width: 20px; height: 20px; fill: currentColor; flex-shrink: 0; }
        .nav-dropdown-trigger .chevron-icon { width: 14px; height: 14px; fill: currentColor; transition: transform 0.25s ease; margin-left: 0.1rem; }
        .nav-dropdown-group:hover .nav-dropdown-trigger,
        .nav-dropdown-group:focus-within .nav-dropdown-trigger,
        .nav-dropdown-group.is-open .nav-dropdown-trigger,
        .nav-dropdown-trigger.active { background: rgba(255, 255, 255, 0.2); color: #fff; }
        .nav-dropdown-group:hover .chevron-icon,
        .nav-dropdown-group:focus-within .chevron-icon,
        .nav-dropdown-group.is-open .chevron-icon { transform: rotate(180deg); }

        /* ── Dropdown panel ── */
        .nav-dropdown {
            position: absolute;
            top: calc(100% + 0.15rem);
            left: 50%;
            transform: translateX(-50%) translateY(-6px);
            background: var(--dropdown-bg);
            border: 1px solid var(--dropdown-border);
            border-radius: 16px;
            padding: 0.4rem;
            list-style: none;
            min-width: 180px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.35);
            backdrop-filter: blur(20px);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease, transform 0.2s ease;
            z-index: 300;
        }
        .nav-dropdown-group:hover .nav-dropdown,
        .nav-dropdown-group:focus-within .nav-dropdown,
        .nav-dropdown-group.is-open .nav-dropdown {
            opacity: 1;
            pointer-events: auto;
            transform: translateX(-50%) translateY(0);
        }
        .nav-dropdown li { list-style: none; }
        .nav-dropdown-link {
            display: flex; align-items: center; gap: 0.6rem;
            padding: 0.55rem 0.9rem;
            color: rgba(255, 255, 255, 0.82);
            text-decoration: none;
            border-radius: 10px;
            font-size: 0.85rem; font-weight: 500;
            transition: all 0.18s ease;
            white-space: nowrap;
        }
        .nav-dropdown-link svg { width: 18px; height: 18px; fill: currentColor; flex-shrink: 0; }
        .nav-dropdown-link:hover, .nav-dropdown-link.active { background: rgba(255,255,255,0.14); color: #fff; }
        .nav-dropdown-divider { height: 1px; background: rgba(255,255,255,0.1); margin: 0.3rem 0.5rem; }

        /* ── User section (right side) ── */
        .navbar-user { display: flex; align-items: center; gap: 0.35rem; }
        .user-profile-link,
        .user-trigger {
            display: flex; align-items: center; gap: 0.75rem; cursor: pointer; padding: 0.4rem 0.6rem;
            border-radius: 50px; transition: background 0.25s; background: none; border: none;
            font-family: 'Poppins', sans-serif;
        }
        .user-profile-link { text-decoration: none; }
        .user-profile-link:hover,
        .user-profile-link.active {
            background: rgba(255,255,255,0.12);
        }
        .user-trigger:hover,
        .user-trigger.active,
        .navbar-user:hover .user-trigger,
        .navbar-user:focus-within .user-trigger,
        .navbar-user.is-open .user-trigger { background: rgba(255,255,255,0.12); }
        .user-name { color: var(--text-primary); font-size: 0.88rem; font-weight: 500; }
        .user-avatar { width: 36px; height: 36px; border-radius: 50%; background: rgba(255, 255, 255, 0.25); display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; }
        .user-avatar svg { width: 20px; height: 20px; fill: rgba(255, 255, 255, 0.85); }
        .user-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .user-chevron { width: 14px; height: 14px; fill: rgba(255,255,255,0.6); transition: transform 0.25s; flex-shrink: 0; }
        .navbar-user:hover .user-chevron,
        .navbar-user:focus-within .user-chevron,
        .navbar-user.is-open .user-chevron { transform: rotate(180deg); }

        .user-dropdown {
            position: absolute;
            top: calc(100% + 0.15rem);
            right: 0;
            background: var(--dropdown-bg);
            border: 1px solid var(--dropdown-border);
            border-radius: 16px;
            padding: 0.4rem;
            list-style: none;
            min-width: 170px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.35);
            backdrop-filter: blur(20px);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease, transform 0.2s ease;
            transform: translateY(-6px);
            z-index: 300;
        }
        .navbar-user:hover .user-dropdown,
        .navbar-user:focus-within .user-dropdown,
        .navbar-user.is-open .user-dropdown {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(0);
        }
        .user-dropdown li { list-style: none; }
        .user-dropdown-link {
            display: flex; align-items: center; gap: 0.6rem;
            padding: 0.55rem 0.9rem;
            color: rgba(255, 255, 255, 0.82);
            text-decoration: none;
            border-radius: 10px;
            font-size: 0.85rem; font-weight: 500;
            transition: all 0.18s ease;
            white-space: nowrap;
            width: 100%;
        }
        .user-dropdown-link svg { width: 18px; height: 18px; fill: currentColor; flex-shrink: 0; }
        .user-dropdown-link:hover, .user-dropdown-link.active { background: rgba(255,255,255,0.14); color: #fff; }
        .user-dropdown-btn {
            background: none; border: none; cursor: pointer;
            font-family: 'Poppins', sans-serif;
            width: 100%;
            text-align: left;
        }
        .user-dropdown-btn:hover .user-dropdown-link { background: rgba(255,255,255,0.14); color: #fff; }
        .user-dropdown-divider { height: 1px; background: rgba(255,255,255,0.1); margin: 0.3rem 0.5rem; }

        /* ── Content & cards ── */
        .main-content { flex: 1; padding: 2rem; max-width: 1180px; width: 100%; margin: 0 auto; }
        .card { background: var(--card-bg); backdrop-filter: blur(15px); border: 1px solid var(--card-border); border-radius: 20px; transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .card:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15); }
        .success-toast, .error-toast { color: #fff; padding: 0.75rem 1.25rem; border-radius: 12px; font-size: 0.88rem; margin-bottom: 1.5rem; text-align: center; }
        .success-toast { background: rgba(46, 204, 113, 0.2); border: 2px solid rgba(46, 204, 113, 0.5); }
        .error-toast { background: rgba(231, 76, 60, 0.18); border: 2px solid rgba(231, 76, 60, 0.45); }

        /* ── Mobile nav ── */
        .mobile-nav { display: none; position: fixed; bottom: 0; left: 0; right: 0; background: var(--mobile-nav-bg); backdrop-filter: blur(20px); padding: 0.5rem 0; z-index: 100; border-top: 1px solid rgba(255, 255, 255, 0.1); }
        .mobile-nav-links { display: flex; justify-content: space-around; list-style: none; }
        .mobile-nav-link { display: flex; flex-direction: column; align-items: center; gap: 0.2rem; color: rgba(255, 255, 255, 0.6); text-decoration: none; font-size: 0.68rem; padding: 0.3rem 0.75rem; transition: color 0.3s ease; }
        .mobile-nav-link.active, .mobile-nav-link:hover { color: #fff; }

        @media (max-width: 768px) {
            .navbar-nav { display: none; }
            .mobile-nav { display: block; }
            body { padding-bottom: 74px; }
            .navbar-user .user-name { display: none; }
            .user-profile-link { display: none; }
            .navbar-user .user-chevron { display: none; }
            .main-content { padding: 1rem; }
            .navbar { padding: 0.75rem 1rem; }
            .user-dropdown { min-width: 190px; }
        }

        @yield('styles')
    </style>
</head>
<body class="{{ auth()->check() && auth()->user()->dark_mode ? 'dark-mode' : '' }}">
    @auth
    @php
        $unreadNotifCount = \App\Models\Notification::where('user_id', Auth::id())->where('lu', false)->count();
    @endphp
    @endauth

    <nav class="navbar">
        <a href="{{ route('home') }}" class="navbar-brand">Meetly</a>

        <ul class="navbar-nav">
            {{-- Discover --}}
            <li><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27A6.47 6.47 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16a6.47 6.47 0 0 0 4.23-1.57l.27.28v.79L20 21.5 21.5 20l-6-6zM9.5 14A4.5 4.5 0 1 1 14 9.5 4.5 4.5 0 0 1 9.5 14z"/></svg>
                {{ __('app.nav_discover') }}
            </a></li>

            {{-- Messages --}}
            <li>
                <a href="{{ route('chats') }}" class="nav-link {{ request()->routeIs('chats*') ? 'active' : '' }}">
                    <span class="nav-link-wrap">
                        <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/></svg>
                        {{ __('app.nav_messages') }}
                        @auth @if(($unreadNotifCount ?? 0) > 0) <span class="nav-badge">{{ $unreadNotifCount > 9 ? '9+' : $unreadNotifCount }}</span> @endif @endauth
                    </span>
                </a>
            </li>

            {{-- Community dropdown: Groups · Events · Interests --}}
            <li><a href="{{ route('groups.index') }}" class="nav-link {{ request()->routeIs('groups*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                {{ __('app.nav_groups') }}
            </a></li>

            <li><a href="{{ route('events.index') }}" class="nav-link {{ request()->routeIs('events*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>
                {{ __('app.nav_events') }}
            </a></li>

            {{-- Admin (admin only) --}}
            @if (Auth::user()->role === 'admin')
                <li><a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
                    {{ __('app.nav_admin') }}
                </a></li>
            @endif
        </ul>

        {{-- User section with dropdown --}}
        <div class="navbar-user" data-dropdown-root>
            <a href="{{ route('profile.edit') }}" class="user-profile-link {{ request()->routeIs('profile.*', 'interets.*') ? 'active' : '' }}">
                <span class="user-name">{{ Auth::user()->prenom }}</span>
            </a>
            <button class="user-trigger {{ request()->routeIs('profile.*', 'interets.*') ? 'active' : '' }}" type="button" aria-haspopup="true" aria-expanded="false" data-dropdown-trigger>
                <div class="user-avatar">
                    <img
                        src="{{ Auth::user()->avatar_url ? route('media.public', ['path' => Auth::user()->avatar_url]) : '' }}"
                        alt=""
                        style="{{ Auth::user()->avatar_url ? '' : 'display:none;' }}"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                    >
                    <svg viewBox="0 0 24 24" style="{{ Auth::user()->avatar_url ? 'display:none;' : '' }}"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                </div>
                <svg class="user-chevron" viewBox="0 0 24 24"><path d="M7 10l5 5 5-5z"/></svg>
            </button>
            <ul class="user-dropdown" role="menu">
                <li>
                    <a href="{{ route('profile.edit') }}" class="user-dropdown-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" role="menuitem">
                        <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                        {{ __('app.nav_profile') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('interets.index') }}" class="user-dropdown-link {{ request()->routeIs('interets.*') ? 'active' : '' }}" role="menuitem">
                        <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-9 14H7v-7h3v7zm7 0h-3V7h3v10z"/></svg>
                        {{ __('app.nav_interests') }}
                    </a>
                </li>
                <li><div class="user-dropdown-divider"></div></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="user-dropdown-btn" role="menuitem">
                            <span class="user-dropdown-link">
                                <svg viewBox="0 0 24 24"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5-5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/></svg>
                                {{ __('app.logout') }}
                            </span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <div class="main-content">
        @if (session('success')) <div class="success-toast">{{ session('success') }}</div> @endif
        @if (session('error')) <div class="error-toast">{{ session('error') }}</div> @endif
        @yield('content')
    </div>

    {{-- Mobile bottom nav --}}
    <nav class="mobile-nav">
        <ul class="mobile-nav-links">
            <li><a href="{{ route('home') }}" class="mobile-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27A6.47 6.47 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16a6.47 6.47 0 0 0 4.23-1.57l.27.28v.79L20 21.5 21.5 20l-6-6zM9.5 14A4.5 4.5 0 1 1 14 9.5 4.5 4.5 0 0 1 9.5 14z"/></svg>
                {{ __('app.nav_discover') }}
            </a></li>
            <li>
                <a href="{{ route('chats') }}" class="mobile-nav-link {{ request()->routeIs('chats*') ? 'active' : '' }}" style="position:relative;">
                    <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/></svg>
                    {{ __('app.nav_messages') }}
                    @auth @if(($unreadNotifCount ?? 0) > 0) <span class="nav-badge" style="position:absolute;top:0;right:0;">{{ $unreadNotifCount > 9 ? '9+' : $unreadNotifCount }}</span> @endif @endauth
                </a>
            </li>
            <li><a href="{{ route('groups.index') }}" class="mobile-nav-link {{ request()->routeIs('groups*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                {{ __('app.nav_groups') }}
            </a></li>
            <li><a href="{{ route('events.index') }}" class="mobile-nav-link {{ request()->routeIs('events*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>
                {{ __('app.nav_events') }}
            </a></li>
            <li><a href="{{ route('profile.edit') }}" class="mobile-nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                {{ __('app.nav_profile') }}
            </a></li>
        </ul>
    </nav>

    <script>
        document.querySelectorAll('[data-dropdown-root]').forEach((dropdownRoot) => {
            const trigger = dropdownRoot.querySelector('[data-dropdown-trigger]');
            if (!trigger) {
                return;
            }

            trigger.addEventListener('click', (event) => {
                event.stopPropagation();
                const willOpen = !dropdownRoot.classList.contains('is-open');

                document.querySelectorAll('[data-dropdown-root].is-open').forEach((openDropdown) => {
                    if (openDropdown !== dropdownRoot) {
                        openDropdown.classList.remove('is-open');
                        const openTrigger = openDropdown.querySelector('[data-dropdown-trigger]');
                        if (openTrigger) {
                            openTrigger.setAttribute('aria-expanded', 'false');
                        }
                    }
                });

                dropdownRoot.classList.toggle('is-open', willOpen);
                trigger.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
            });
        });

        document.addEventListener('click', (event) => {
            document.querySelectorAll('[data-dropdown-root].is-open').forEach((dropdownRoot) => {
                if (!dropdownRoot.contains(event.target)) {
                    dropdownRoot.classList.remove('is-open');
                    const trigger = dropdownRoot.querySelector('[data-dropdown-trigger]');
                    if (trigger) {
                        trigger.setAttribute('aria-expanded', 'false');
                    }
                }
            });
        });

        document.addEventListener('keydown', (event) => {
            if (event.key !== 'Escape') {
                return;
            }

            document.querySelectorAll('[data-dropdown-root].is-open').forEach((dropdownRoot) => {
                dropdownRoot.classList.remove('is-open');
                const trigger = dropdownRoot.querySelector('[data-dropdown-trigger]');
                if (trigger) {
                    trigger.setAttribute('aria-expanded', 'false');
                }
            });
        });
    </script>

    @yield('scripts')
</body>
</html>
