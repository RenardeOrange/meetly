@extends('layouts.app')

@section('title', 'Admin utilisateurs')

@section('styles')
<style>
    .admin-users { display: grid; gap: 1.5rem; }
    .panel { padding: 1.4rem; }
    .panel h1 { color: #fff; margin-bottom: 1rem; }
    .search-bar { display: flex; gap: 0.75rem; flex-wrap: wrap; }
    .search-bar input { flex: 1 1 280px; padding: 0.85rem 1rem; border-radius: 16px; border: 1px solid rgba(255,255,255,0.2); background: rgba(255,255,255,0.12); color: #fff; outline: none; font-family: 'Poppins', sans-serif; }
    .search-bar input::placeholder { color: rgba(255,255,255,0.55); }
    .search-bar button, .back-link { border: none; border-radius: 999px; padding: 0.8rem 1.4rem; font-weight: 700; cursor: pointer; text-decoration: none; font-family: 'Poppins', sans-serif; }
    .search-bar button { background: #fff; color: #c0392b; }
    .back-link { background: rgba(255,255,255,0.15); color: #fff; display: inline-flex; align-items: center; }
    .users-list { display: grid; gap: 1.2rem; }
    .user-card { padding: 1.2rem; border-radius: 18px; background: rgba(255,255,255,0.08); }
    .user-card-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 0.9rem; }
    .user-card-avatar { width: 54px; height: 54px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; }
    .user-card-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .user-card-avatar svg { width: 26px; height: 26px; fill: rgba(255,255,255,0.8); }
    .user-card-info { flex: 1; min-width: 0; }
    .user-card-name { color: #fff; font-weight: 700; font-size: 1rem; }
    .user-card-meta { color: rgba(255,255,255,0.65); font-size: 0.8rem; margin-top: 0.15rem; }
    .user-card-bio { color: rgba(255,255,255,0.72); font-size: 0.82rem; font-style: italic; margin-bottom: 0.8rem; }
    .user-badges { display: flex; flex-wrap: wrap; gap: 0.4rem; margin-bottom: 0.8rem; }
    .badge { padding: 0.25rem 0.65rem; border-radius: 999px; font-size: 0.72rem; font-weight: 600; }
    .badge-blacklisted { background: rgba(231,76,60,0.3); color: #e74c3c; border: 1px solid rgba(231,76,60,0.5); }
    .badge-active { background: rgba(46,204,113,0.2); color: #2ecc71; border: 1px solid rgba(46,204,113,0.4); }
    .badge-admin { background: rgba(241,196,15,0.2); color: #f1c40f; border: 1px solid rgba(241,196,15,0.4); }
    .badge-interests { background: rgba(255,255,255,0.12); color: rgba(255,255,255,0.85); border: 1px solid rgba(255,255,255,0.2); }
    .update-form { display: grid; grid-template-columns: repeat(6, minmax(0,1fr)) auto; gap: 0.65rem; align-items: center; margin-bottom: 0.75rem; }
    .update-form input, .update-form select { width: 100%; padding: 0.7rem 0.9rem; border-radius: 14px; border: 1px solid rgba(255,255,255,0.18); background: rgba(255,255,255,0.12); color: #fff; font-family: 'Poppins', sans-serif; outline: none; }
    .update-form select option { color: #222; }
    .update-form button { border: none; border-radius: 999px; padding: 0.7rem 1.1rem; font-weight: 700; cursor: pointer; background: #fff; color: #c0392b; font-family: 'Poppins', sans-serif; white-space: nowrap; }
    .action-row { display: flex; gap: 0.65rem; flex-wrap: wrap; }
    .action-btn { border: none; border-radius: 999px; padding: 0.6rem 1.1rem; font-weight: 600; cursor: pointer; font-family: 'Poppins', sans-serif; font-size: 0.82rem; }
    .action-btn-blacklist { background: rgba(231,76,60,0.18); color: #e74c3c; border: 1px solid rgba(231,76,60,0.4); }
    .action-btn-unblacklist { background: rgba(46,204,113,0.18); color: #2ecc71; border: 1px solid rgba(46,204,113,0.4); }
    .action-btn-delete { background: rgba(231,76,60,0.28); color: #fff; }
    .stats-row { display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1rem; }
    .stat-chip { padding: 0.4rem 1rem; border-radius: 999px; background: rgba(255,255,255,0.1); color: #fff; font-size: 0.82rem; font-weight: 600; }
    @media (max-width: 1200px) { .update-form { grid-template-columns: repeat(3, minmax(0,1fr)); } }
    @media (max-width: 700px) { .update-form { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 480px) { .update-form { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<div class="admin-users">
    <div class="card panel">
        <h1>Panneau administrateur — Comptes utilisateurs</h1>
        <div class="stats-row">
            <span class="stat-chip">{{ $users->count() }} utilisateur(s)</span>
            <span class="stat-chip">{{ $users->where('blacklisted', true)->count() }} blackliste(s)</span>
            <span class="stat-chip">{{ $users->where('role', 'admin')->count() }} admin(s)</span>
        </div>
        <form method="GET" action="{{ route('admin.users') }}" class="search-bar">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Chercher par nom, prenom ou courriel">
            <button type="submit">Chercher</button>
            <a href="{{ route('admin.dashboard') }}" class="back-link">Tableau de bord</a>
        </form>
    </div>

    <div class="card panel">
        <div class="users-list">
            @foreach ($users as $user)
                <div class="user-card">
                    <div class="user-card-header">
                        <div class="user-card-avatar">
                            @if ($user->avatar_url)
                                <img src="{{ asset('storage/' . $user->avatar_url) }}" alt="Avatar">
                            @else
                                <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                            @endif
                        </div>
                        <div class="user-card-info">
                            <div class="user-card-name">{{ $user->prenom }} {{ $user->nom }}</div>
                            <div class="user-card-meta">{{ $user->email }} &bull; {{ $user->position === 'etudiant' ? 'Etudiant(e)' : 'Personnel' }}@if($user->numero_programme) &bull; {{ $user->numero_programme }}@endif</div>
                        </div>
                    </div>

                    @if ($user->bio)
                        <div class="user-card-bio">"{{ $user->bio }}"</div>
                    @endif

                    <div class="user-badges">
                        <span class="badge {{ $user->blacklisted ? 'badge-blacklisted' : 'badge-active' }}">
                            {{ $user->blacklisted ? 'Blackliste' : 'Actif' }}
                        </span>
                        @if ($user->role === 'admin')
                            <span class="badge badge-admin">Admin</span>
                        @endif
                        <span class="badge badge-interests">{{ $user->interets->count() }} interet(s)</span>
                        @if ($user->genre)
                            <span class="badge badge-interests">{{ ucfirst($user->genre) }}</span>
                        @endif
                        @if ($user->orientation)
                            <span class="badge badge-interests">{{ ucfirst($user->orientation) }}</span>
                        @endif
                        @if ($user->email_verified_at)
                            <span class="badge badge-active">Courriel verifie</span>
                        @else
                            <span class="badge badge-blacklisted">Non verifie</span>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="update-form">
                        @csrf
                        @method('PUT')
                        <input type="text" name="prenom" value="{{ $user->prenom }}" placeholder="Prenom" required>
                        <input type="text" name="nom" value="{{ $user->nom }}" placeholder="Nom" required>
                        <select name="role" required>
                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        <select name="position" required>
                            <option value="etudiant" {{ $user->position === 'etudiant' ? 'selected' : '' }}>Etudiant</option>
                            <option value="personnel" {{ $user->position === 'personnel' ? 'selected' : '' }}>Personnel</option>
                        </select>
                        <select name="genre">
                            <option value="" {{ !$user->genre ? 'selected' : '' }}>Genre —</option>
                            <option value="homme" {{ $user->genre === 'homme' ? 'selected' : '' }}>Homme</option>
                            <option value="femme" {{ $user->genre === 'femme' ? 'selected' : '' }}>Femme</option>
                            <option value="non-binaire" {{ $user->genre === 'non-binaire' ? 'selected' : '' }}>Non-binaire</option>
                            <option value="autre" {{ $user->genre === 'autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                        <select name="orientation">
                            <option value="" {{ !$user->orientation ? 'selected' : '' }}>Orientation —</option>
                            <option value="heterosexuel" {{ $user->orientation === 'heterosexuel' ? 'selected' : '' }}>Heterosexuel(le)</option>
                            <option value="homosexuel" {{ $user->orientation === 'homosexuel' ? 'selected' : '' }}>Homosexuel(le)</option>
                            <option value="bisexuel" {{ $user->orientation === 'bisexuel' ? 'selected' : '' }}>Bisexuel(le)</option>
                            <option value="pansexuel" {{ $user->orientation === 'pansexuel' ? 'selected' : '' }}>Pansexuel(le)</option>
                            <option value="autre" {{ $user->orientation === 'autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                        <button type="submit">Modifier</button>
                    </form>

                    <div class="action-row">
                        <form method="POST" action="{{ route('admin.users.blacklist', $user) }}">
                            @csrf
                            <button type="submit" class="action-btn {{ $user->blacklisted ? 'action-btn-unblacklist' : 'action-btn-blacklist' }}">
                                {{ $user->blacklisted ? 'Retirer blacklist' : 'Blacklister' }}
                            </button>
                        </form>
                        @if ($user->role !== 'admin')
                            <form method="POST" action="{{ route('admin.users.delete', $user) }}" onsubmit="return confirm('Supprimer definitivement {{ $user->prenom }} {{ $user->nom }} ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn action-btn-delete">Supprimer</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
