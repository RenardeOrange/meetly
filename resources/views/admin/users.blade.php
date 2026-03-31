@extends('layouts.app')

@section('title', 'Admin utilisateurs')

@section('styles')
<style>
    .admin-users { display: grid; gap: 1.5rem; }
    .panel { padding: 1.4rem; }
    .panel h1 { color: #fff; margin-bottom: 1rem; }
    .search-bar { display: flex; gap: 0.75rem; flex-wrap: wrap; }
    .search-bar input { flex: 1 1 280px; padding: 0.85rem 1rem; border-radius: 16px; border: 1px solid rgba(255, 255, 255, 0.2); background: rgba(255, 255, 255, 0.12); color: #fff; outline: none; }
    .search-bar button, .back-link, .update-form button, .action-form button { border: none; border-radius: 999px; padding: 0.8rem 1rem; font-weight: 700; cursor: pointer; text-decoration: none; }
    .search-bar button, .update-form button { background: #fff; color: #c0392b; }
    .back-link, .action-form button { background: rgba(255, 255, 255, 0.15); color: #fff; }
    .users-list { display: grid; gap: 1rem; }
    .user-card { padding: 1rem; border-radius: 18px; background: rgba(255, 255, 255, 0.08); }
    .user-meta { color: rgba(255, 255, 255, 0.72); font-size: 0.86rem; margin-bottom: 0.9rem; }
    .update-form { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)) auto; gap: 0.75rem; align-items: center; }
    .update-form input, .update-form select { width: 100%; padding: 0.75rem 0.9rem; border-radius: 14px; border: 1px solid rgba(255, 255, 255, 0.18); background: rgba(255, 255, 255, 0.12); color: #fff; }
    .update-form select option { color: #222; }
    .action-row { display: flex; gap: 0.65rem; margin-top: 0.75rem; flex-wrap: wrap; }
    @media (max-width: 980px) { .update-form { grid-template-columns: 1fr 1fr; } }
</style>
@endsection

@section('content')
<div class="admin-users">
    <div class="card panel">
        <h1>Comptes utilisateurs</h1>
        <form method="GET" action="{{ route('admin.users') }}" class="search-bar">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Chercher par nom ou courriel">
            <button type="submit">Chercher</button>
            <a href="{{ route('admin.dashboard') }}" class="back-link">Retour admin</a>
        </form>
    </div>

    <div class="card panel">
        <div class="users-list">
            @foreach ($users as $user)
                <div class="user-card">
                    <div class="user-meta">{{ $user->email }} | {{ $user->blacklisted ? 'Blackliste' : 'Actif' }}</div>
                    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="update-form">
                        @csrf
                        @method('PUT')
                        <input type="text" name="prenom" value="{{ $user->prenom }}" required>
                        <input type="text" name="nom" value="{{ $user->nom }}" required>
                        <select name="role" required>
                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        <select name="position" required>
                            <option value="etudiant" {{ $user->position === 'etudiant' ? 'selected' : '' }}>Etudiant</option>
                            <option value="personnel" {{ $user->position === 'personnel' ? 'selected' : '' }}>Personnel</option>
                        </select>
                        <button type="submit">Mettre a jour</button>
                    </form>
                    <div class="action-row">
                        <form method="POST" action="{{ route('admin.users.blacklist', $user) }}" class="action-form">
                            @csrf
                            <button type="submit">{{ $user->blacklisted ? 'Retirer blacklist' : 'Ajouter blacklist' }}</button>
                        </form>
                        @if ($user->role !== 'admin')
                            <form method="POST" action="{{ route('admin.users.delete', $user) }}" class="action-form" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Supprimer</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
