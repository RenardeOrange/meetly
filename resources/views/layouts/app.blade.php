<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Meetly - @yield('title', 'Accueil')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; min-height: 100vh; background: linear-gradient(135deg, #f97316, #ef4444, #ec4899); background-size: 200% 200%; animation: gradientShift 8s ease infinite; display: flex; flex-direction: column; }
        @keyframes gradientShift { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
        .navbar { display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 2rem; background: rgba(255, 255, 255, 0.12); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(255, 255, 255, 0.15); position: sticky; top: 0; z-index: 100; }
        .navbar-brand { font-size: 1.5rem; font-weight: 700; color: #fff; text-decoration: none; letter-spacing: 2px; text-transform: uppercase; }
        .navbar-nav { display: flex; gap: 0.25rem; list-style: none; }
        .nav-link { display: flex; align-items: center; gap: 0.4rem; padding: 0.6rem 1.1rem; color: rgba(255, 255, 255, 0.8); text-decoration: none; border-radius: 50px; font-size: 0.88rem; font-weight: 500; transition: all 0.3s ease; }
        .nav-link:hover, .nav-link.active { background: rgba(255, 255, 255, 0.2); color: #fff; }
        .nav-link svg, .mobile-nav-link svg { width: 20px; height: 20px; fill: currentColor; }
        .navbar-user { display: flex; align-items: center; gap: 0.75rem; }
        .user-name { color: #fff; font-size: 0.88rem; font-weight: 500; }
        .user-avatar { width: 36px; height: 36px; border-radius: 50%; background: rgba(255, 255, 255, 0.25); display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .user-avatar svg { width: 20px; height: 20px; fill: rgba(255, 255, 255, 0.85); }
        .btn-logout { background: none; border: 1.5px solid rgba(255, 255, 255, 0.5); color: #fff; padding: 0.4rem 1rem; border-radius: 50px; font-size: 0.82rem; font-family: 'Poppins', sans-serif; font-weight: 500; cursor: pointer; transition: all 0.3s ease; }
        .btn-logout:hover { background: rgba(255, 255, 255, 0.15); border-color: #fff; }
        .nav-badge { display: inline-flex; align-items: center; justify-content: center; background: #e74c3c; color: #fff; border-radius: 999px; min-width: 18px; height: 18px; font-size: 0.65rem; font-weight: 700; padding: 0 4px; margin-left: 2px; line-height: 1; }
        .nav-link-wrap { position: relative; display: flex; align-items: center; gap: 0.4rem; }
        .main-content { flex: 1; padding: 2rem; max-width: 1180px; width: 100%; margin: 0 auto; }
        .mobile-nav { display: none; position: fixed; bottom: 0; left: 0; right: 0; background: rgba(44, 62, 80, 0.95); backdrop-filter: blur(20px); padding: 0.5rem 0; z-index: 100; border-top: 1px solid rgba(255, 255, 255, 0.1); }
        .mobile-nav-links { display: flex; justify-content: space-around; list-style: none; }
        .mobile-nav-link { display: flex; flex-direction: column; align-items: center; gap: 0.2rem; color: rgba(255, 255, 255, 0.6); text-decoration: none; font-size: 0.68rem; padding: 0.3rem 0.75rem; transition: color 0.3s ease; }
        .mobile-nav-link.active, .mobile-nav-link:hover { color: #fff; }
        .card { background: rgba(255, 255, 255, 0.12); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 20px; transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .card:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15); }
        .success-toast, .error-toast { color: #fff; padding: 0.75rem 1.25rem; border-radius: 12px; font-size: 0.88rem; margin-bottom: 1.5rem; text-align: center; }
        .success-toast { background: rgba(46, 204, 113, 0.2); border: 2px solid rgba(46, 204, 113, 0.5); }
        .error-toast { background: rgba(231, 76, 60, 0.18); border: 2px solid rgba(231, 76, 60, 0.45); }
        @media (max-width: 768px) { .navbar-nav { display: none; } .mobile-nav { display: block; } body { padding-bottom: 74px; } .navbar-user .user-name { display: none; } .main-content { padding: 1rem; } .navbar { padding: 0.75rem 1rem; } }
        @yield('styles')
    </style>
</head>
<body>
    @auth
    @php
        $unreadNotifCount = \App\Models\Notification::where('user_id', Auth::id())->where('lu', false)->count();
    @endphp
    @endauth
    <nav class="navbar">
        <a href="{{ route('home') }}" class="navbar-brand">Meetly</a>
        <ul class="navbar-nav">
            <li><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>Decouvrir</a></li>
            <li>
                <a href="{{ route('chats') }}" class="nav-link {{ request()->routeIs('chats*') ? 'active' : '' }}">
                    <span class="nav-link-wrap">
                        <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/></svg>
                        Messages
                        @auth @if(($unreadNotifCount ?? 0) > 0) <span class="nav-badge">{{ $unreadNotifCount > 9 ? '9+' : $unreadNotifCount }}</span> @endif @endauth
                    </span>
                </a>
            </li>
            <li><a href="{{ route('groups.index') }}" class="nav-link {{ request()->routeIs('groups*') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>Groupes</a></li>
            <li><a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>Profil</a></li>
            <li><a href="{{ route('interets.index') }}" class="nav-link {{ request()->routeIs('interets.*') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-9 14H7v-7h3v7zm7 0h-3V7h3v10z"/></svg>Interets</a></li>
            @if (Auth::user()->role === 'admin')
                <li><a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>Admin</a></li>
            @endif
        </ul>
        <div class="navbar-user">
            <span class="user-name">{{ Auth::user()->prenom }}</span>
            <div class="user-avatar">
                @if (Auth::user()->avatar_url)
                    <img src="{{ asset('storage/' . Auth::user()->avatar_url) }}" alt="Avatar" style="width:100%;height:100%;object-fit:cover;">
                @else
                    <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                @endif
            </div>
            <form method="POST" action="{{ route('logout') }}">@csrf <button type="submit" class="btn-logout">Deconnexion</button></form>
        </div>
    </nav>
    <div class="main-content">
        @if (session('success')) <div class="success-toast">{{ session('success') }}</div> @endif
        @if (session('error')) <div class="error-toast">{{ session('error') }}</div> @endif
        @yield('content')
    </div>
    <nav class="mobile-nav">
        <ul class="mobile-nav-links">
            <li><a href="{{ route('home') }}" class="mobile-nav-link {{ request()->routeIs('home') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>Découvrir</a></li>
            <li>
                <a href="{{ route('chats') }}" class="mobile-nav-link {{ request()->routeIs('chats*') ? 'active' : '' }}" style="position:relative;">
                    <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/></svg>
                    Messages
                    @auth @if(($unreadNotifCount ?? 0) > 0) <span class="nav-badge" style="position:absolute;top:0;right:0;">{{ $unreadNotifCount > 9 ? '9+' : $unreadNotifCount }}</span> @endif @endauth
                </a>
            </li>
            <li><a href="{{ route('groups.index') }}" class="mobile-nav-link {{ request()->routeIs('groups*') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>Groupes</a></li>
            <li><a href="{{ route('profile.edit') }}" class="mobile-nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}"><svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>Profil</a></li>
        </ul>
    </nav>
    @yield('scripts')
</body>
</html>
