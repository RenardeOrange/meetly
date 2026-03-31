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

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #f97316, #ef4444, #ec4899);
            background-size: 200% 200%;
            animation: gradientShift 8s ease infinite;
            display: flex;
            flex-direction: column;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* ── Navbar ── */
        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 2rem;
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            text-decoration: none;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .navbar-nav {
            display: flex;
            gap: 0.25rem;
            list-style: none;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.6rem 1.1rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: 50px;
            font-size: 0.88rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .nav-link svg {
            width: 20px;
            height: 20px;
            fill: currentColor;
        }

        .navbar-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-name {
            color: #fff;
            font-size: 0.88rem;
            font-weight: 500;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .user-avatar svg {
            width: 20px;
            height: 20px;
            fill: rgba(255, 255, 255, 0.85);
        }

        .btn-logout {
            background: none;
            border: 1.5px solid rgba(255, 255, 255, 0.5);
            color: #fff;
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-size: 0.82rem;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: #fff;
        }

        /* ── Content ── */
        .main-content {
            flex: 1;
            padding: 2rem;
            max-width: 900px;
            width: 100%;
            margin: 0 auto;
        }

        /* ── Mobile bottom nav ── */
        .mobile-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(44, 62, 80, 0.95);
            backdrop-filter: blur(20px);
            padding: 0.5rem 0;
            z-index: 100;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .mobile-nav-links {
            display: flex;
            justify-content: space-around;
            list-style: none;
        }

        .mobile-nav-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.2rem;
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            font-size: 0.68rem;
            padding: 0.3rem 0.75rem;
            transition: color 0.3s ease;
        }

        .mobile-nav-link.active, .mobile-nav-link:hover {
            color: #fff;
        }

        .mobile-nav-link svg {
            width: 22px;
            height: 22px;
            fill: currentColor;
        }

        @media (max-width: 768px) {
            .navbar-nav { display: none; }
            .mobile-nav { display: block; }
            body { padding-bottom: 70px; }
            .navbar-user .user-name { display: none; }
            .main-content { padding: 1rem; }
        }

        /* ── Shared utilities ── */
        .card {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 1.5rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        }

        .success-toast {
            background: rgba(46, 204, 113, 0.2);
            border: 2px solid rgba(46, 204, 113, 0.5);
            color: #fff;
            padding: 0.75rem 1.25rem;
            border-radius: 12px;
            font-size: 0.88rem;
            margin-bottom: 1.5rem;
            text-align: center;
            animation: fadeInDown 0.4s ease;
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @yield('styles')
    </style>
</head>
<body>
    <!-- Desktop Navbar -->
    <nav class="navbar">
        <a href="{{ route('home') }}" class="navbar-brand">Meetly</a>

        <ul class="navbar-nav">
            <li>
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    D&eacute;couvrir
                </a>
            </li>
            <li>
                <a href="{{ route('chats') }}" class="nav-link {{ request()->routeIs('chats') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/></svg>
                    Messages
                </a>
            </li>
            <li>
                <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                    Profil
                </a>
            </li>
        </ul>

        <div class="navbar-user">
            <span class="user-name">{{ Auth::user()->prenom }}</span>
            <div class="user-avatar">
                <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">D&eacute;connexion</button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>

    <!-- Mobile Bottom Nav -->
    <nav class="mobile-nav">
        <ul class="mobile-nav-links">
            <li>
                <a href="{{ route('home') }}" class="mobile-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    D&eacute;couvrir
                </a>
            </li>
            <li>
                <a href="{{ route('chats') }}" class="mobile-nav-link {{ request()->routeIs('chats') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/></svg>
                    Messages
                </a>
            </li>
            <li>
                <a href="{{ route('profile.edit') }}" class="mobile-nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                    Profil
                </a>
            </li>
        </ul>
    </nav>

    @yield('scripts')
</body>
</html>
