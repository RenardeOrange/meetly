@extends('layouts.app')

@section('title', 'Admin — Groupes')

@section('styles')
<style>
    .admin-groups { display: grid; gap: 1.5rem; }
    .panel { padding: 1.4rem; }
    .panel h1 { color: #fff; margin-bottom: 1rem; }
    .search-bar { display: flex; gap: 0.75rem; flex-wrap: wrap; }
    .search-bar input { flex: 1 1 280px; padding: 0.85rem 1rem; border-radius: 16px; border: 1px solid rgba(255,255,255,0.2); background: rgba(255,255,255,0.12); color: #fff; outline: none; font-family: 'Poppins', sans-serif; }
    .search-bar input::placeholder { color: rgba(255,255,255,0.55); }
    .search-bar button, .back-link { border: none; border-radius: 999px; padding: 0.8rem 1.4rem; font-weight: 700; cursor: pointer; text-decoration: none; font-family: 'Poppins', sans-serif; }
    .search-bar button { background: #fff; color: #c0392b; }
    .back-link { background: rgba(255,255,255,0.15); color: #fff; display: inline-flex; align-items: center; }
    .stats-row { display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1rem; }
    .stat-chip { padding: 0.4rem 1rem; border-radius: 999px; background: rgba(255,255,255,0.1); color: #fff; font-size: 0.82rem; font-weight: 600; }
    .groups-list { display: grid; gap: 1.2rem; }
    .group-card { padding: 1.2rem; border-radius: 18px; background: rgba(255,255,255,0.08); }
    .group-card-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 0.9rem; }
    .group-avatar { width: 54px; height: 54px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; }
    .group-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .group-avatar svg { width: 26px; height: 26px; fill: rgba(255,255,255,0.8); }
    .group-info { flex: 1; min-width: 0; }
    .group-name { color: #fff; font-weight: 700; font-size: 1rem; }
    .group-meta { color: rgba(255,255,255,0.65); font-size: 0.8rem; margin-top: 0.15rem; }
    .group-badges { display: flex; flex-wrap: wrap; gap: 0.4rem; margin-bottom: 0.8rem; }
    .badge { padding: 0.25rem 0.65rem; border-radius: 999px; font-size: 0.72rem; font-weight: 600; }
    .badge-public  { background: rgba(46,204,113,0.2);  color: #2ecc71; border: 1px solid rgba(46,204,113,0.4); }
    .badge-private { background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.7); border: 1px solid rgba(255,255,255,0.2); }
    .badge-info    { background: rgba(52,152,219,0.2);  color: #3498db; border: 1px solid rgba(52,152,219,0.4); }
    .update-form { display: grid; gap: 0.65rem; margin-bottom: 0.75rem; }
    .form-row-3 { display: grid; grid-template-columns: 1fr 1fr auto auto; gap: 0.65rem; align-items: center; }
    .update-form input, .update-form textarea, .update-form select { width: 100%; padding: 0.7rem 0.9rem; border-radius: 14px; border: 1px solid rgba(255,255,255,0.18); background: rgba(255,255,255,0.12); color: #fff; font-family: 'Poppins', sans-serif; outline: none; font-size: 0.85rem; }
    .update-form textarea { resize: vertical; min-height: 64px; }
    .update-form select option { color: #222; }
    .update-form button { border: none; border-radius: 999px; padding: 0.7rem 1.1rem; font-weight: 700; cursor: pointer; background: #fff; color: #c0392b; font-family: 'Poppins', sans-serif; white-space: nowrap; }
    .action-row { display: flex; gap: 0.65rem; flex-wrap: wrap; }
    .action-btn { border: none; border-radius: 999px; padding: 0.6rem 1.1rem; font-weight: 600; cursor: pointer; font-family: 'Poppins', sans-serif; font-size: 0.82rem; }
    .action-btn-delete { background: rgba(231,76,60,0.28); color: #fff; }
    label.form-label { color: rgba(255,255,255,0.75); font-size: 0.78rem; font-weight: 600; display: block; margin-bottom: 0.25rem; }
    .toggle-row { display: flex; align-items: center; gap: 0.75rem; color: rgba(255,255,255,0.8); font-size: 0.85rem; }
    @media (max-width: 700px) { .form-row-3 { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<div class="admin-groups">
    <div class="card panel">
        <h1>Panneau administrateur — Groupes</h1>
        <div class="stats-row">
            <span class="stat-chip">{{ $groups->count() }} groupe(s)</span>
            <span class="stat-chip">{{ $groups->where('est_public', true)->count() }} public(s)</span>
            <span class="stat-chip">{{ $groups->where('est_public', false)->count() }} prive(s)</span>
        </div>
        <form method="GET" action="{{ route('admin.groups') }}" class="search-bar">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Chercher par nom de groupe">
            <button type="submit">Chercher</button>
            <a href="{{ route('admin.dashboard') }}" class="back-link">Tableau de bord</a>
        </form>
    </div>

    @if(session('success'))
        <div style="background:rgba(46,204,113,0.18);color:#2ecc71;padding:0.85rem 1.2rem;border-radius:14px;border:1px solid rgba(46,204,113,0.35);">
            {{ session('success') }}
        </div>
    @endif

    <div class="card panel">
        <div class="groups-list">
            @forelse($groups as $group)
                <div class="group-card">
                    <div class="group-card-header">
                        <div class="group-avatar">
                            @if($group->avatar_url)
                                <img src="{{ asset('storage/' . $group->avatar_url) }}" alt="Avatar">
                            @else
                                <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                            @endif
                        </div>
                        <div class="group-info">
                            <div class="group-name">{{ $group->nom }}</div>
                            <div class="group-meta">
                                Createur: {{ $group->creator->prenom }} {{ $group->creator->nom }}
                                &bull; {{ $group->members->count() }} membre(s)
                                &bull; Cree le {{ $group->created_at->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>

                    <div class="group-badges">
                        <span class="badge {{ $group->est_public ? 'badge-public' : 'badge-private' }}">
                            {{ $group->est_public ? 'Public' : 'Prive' }}
                        </span>
                        <span class="badge badge-info">{{ $group->members->count() }} membres</span>
                    </div>

                    <form method="POST" action="{{ route('admin.groups.update', $group) }}" class="update-form">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="form-label">Nom</label>
                            <input type="text" name="nom" value="{{ $group->nom }}" maxlength="100" required>
                        </div>
                        <div>
                            <label class="form-label">Description</label>
                            <textarea name="description" maxlength="500">{{ $group->description }}</textarea>
                        </div>
                        <div class="form-row-3">
                            <div class="toggle-row">
                                <label class="form-label" style="margin:0;">Public</label>
                                <select name="est_public">
                                    <option value="1" {{ $group->est_public ? 'selected' : '' }}>Oui</option>
                                    <option value="0" {{ !$group->est_public ? 'selected' : '' }}>Non</option>
                                </select>
                            </div>
                            <div></div>
                            <button type="submit">Modifier</button>
                        </div>
                    </form>

                    <div class="action-row">
                        <form method="POST" action="{{ route('admin.groups.delete', $group) }}"
                              onsubmit="return confirm('Supprimer le groupe « {{ addslashes($group->nom) }} » et tous ses membres/messages ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn action-btn-delete">Supprimer le groupe</button>
                        </form>
                        <a href="{{ route('groups.show', $group) }}" style="color:rgba(255,255,255,0.7);font-size:0.82rem;align-self:center;text-decoration:none;">Voir la page →</a>
                    </div>
                </div>
            @empty
                <div style="color:rgba(255,255,255,0.5);text-align:center;padding:2rem;">Aucun groupe trouvé.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
