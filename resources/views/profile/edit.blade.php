@extends('layouts.app')

@section('title', 'Mon profil')

@section('styles')
<style>
    .profile-page { display: grid; grid-template-columns: 1.15fr 0.85fr; gap: 1.5rem; align-items: start; }
    .profile-panel { padding: 1.5rem; }
    .profile-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; }
    .avatar-large { width: 110px; height: 110px; border-radius: 50%; background: rgba(255, 255, 255, 0.25); display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; }
    .avatar-large svg { width: 50px; height: 50px; fill: rgba(255, 255, 255, 0.85); }
    .profile-header h1 { color: #fff; font-size: 1.5rem; font-weight: 700; margin-bottom: 0.35rem; }
    .profile-header p, .empty-copy { color: rgba(255, 255, 255, 0.74); font-size: 0.9rem; line-height: 1.5; }
    .profile-form { display: grid; gap: 1rem; }
    .profile-form label, .side-title { display: block; color: rgba(255, 255, 255, 0.85); font-size: 0.82rem; font-weight: 600; margin-bottom: 0.35rem; }
    .profile-form input, .profile-form textarea, .profile-form select { width: 100%; padding: 0.85rem 1rem; border: 1px solid rgba(255, 255, 255, 0.24); border-radius: 18px; background: rgba(255, 255, 255, 0.12); color: #fff; outline: none; }
    .profile-form input[type="file"] { padding: 0.75rem 1rem; }
    .profile-form select option { color: #222; }
    .profile-form textarea { resize: vertical; min-height: 110px; }
    .profile-form input::placeholder, .profile-form textarea::placeholder { color: rgba(255, 255, 255, 0.55); }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
    .email-display { padding: 0.85rem 1rem; border-radius: 18px; background: rgba(255, 255, 255, 0.08); color: rgba(255, 255, 255, 0.76); }
    .btn-save, .btn-manage { width: 100%; border: none; border-radius: 999px; padding: 0.9rem 1rem; font-weight: 700; cursor: pointer; text-decoration: none; text-align: center; }
    .btn-save { background: #fff; color: #c0392b; }
    .btn-manage { display: inline-flex; align-items: center; justify-content: center; background: rgba(255, 255, 255, 0.14); color: #fff; margin-top: 1rem; }
    .error-message { background: rgba(231, 76, 60, 0.18); color: #fff; padding: 0.75rem 1rem; border-radius: 12px; border: 1px solid rgba(231, 76, 60, 0.35); }
    .char-count { text-align: right; color: rgba(255, 255, 255, 0.6); font-size: 0.74rem; margin-top: 0.35rem; }
    .side-stack { display: grid; gap: 1.5rem; }
    .interest-chips { display: flex; flex-wrap: wrap; gap: 0.55rem; }
    .interest-chip { padding: 0.45rem 0.8rem; border-radius: 999px; background: rgba(255, 255, 255, 0.18); color: #fff; font-size: 0.78rem; }
    .taxonomy-list { list-style: none; display: grid; gap: 0.85rem; }
    .taxonomy-list li { padding: 0.9rem 1rem; border-radius: 16px; background: rgba(255, 255, 255, 0.08); color: rgba(255, 255, 255, 0.82); font-size: 0.9rem; line-height: 1.5; }
    @media (max-width: 880px) { .profile-page, .form-row { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<div class="profile-page">
    <div class="card profile-panel">
        <div class="profile-header">
            <div class="avatar-large">
                @if ($user->avatar_url)
                    <img src="{{ asset('storage/' . $user->avatar_url) }}" alt="Avatar" style="width:100%;height:100%;object-fit:cover;">
                @else
                    <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                @endif
            </div>
            <div>
                <h1>Mon profil</h1>
                <p>Pr&eacute;nom, nom, programme, bio courte, avatar, visibilit&eacute; et int&eacute;r&ecirc;ts.</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="error-message">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" class="profile-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-row">
                <div><label>Pr&eacute;nom</label><input type="text" name="prenom" value="{{ old('prenom', $user->prenom) }}" required></div>
                <div><label>Nom</label><input type="text" name="nom" value="{{ old('nom', $user->nom) }}" required></div>
            </div>
            <div><label>Courriel</label><div class="email-display">{{ $user->email }}</div></div>
            <div><label>No. de programme</label><input type="text" name="numero_programme" value="{{ old('numero_programme', $user->numero_programme) }}" placeholder="Ex. Techniques de l'informatique"></div>
            <div>
                <label>Bio courte</label>
                <textarea name="bio" id="bioField" maxlength="200" placeholder="Dis quelques mots sur toi.">{{ old('bio', $user->bio) }}</textarea>
                <div class="char-count"><span id="bioCount">{{ strlen(old('bio', $user->bio ?? '')) }}</span>/200</div>
            </div>
            <div><label>Avatar</label><input type="file" name="avatar" accept="image/*"></div>
            <div>
                <label>Visibilit&eacute;</label>
                <select name="visibilite">
                    <option value="public" {{ old('visibilite', $user->visibilite) === 'public' ? 'selected' : '' }}>Public</option>
                    <option value="prive" {{ old('visibilite', $user->visibilite) === 'prive' ? 'selected' : '' }}>Prive</option>
                </select>
            </div>
            <button type="submit" class="btn-save">Enregistrer</button>
        </form>
    </div>

    <div class="side-stack">
        <div class="card profile-panel">
            <div class="side-title">Mes int&eacute;r&ecirc;ts</div>
            @if ($user->interets->isNotEmpty())
                <div class="interest-chips">
                    @foreach ($user->interets->sortBy(fn ($interet) => $interet->categorie . '|' . $interet->nom) as $interet)
                        <span class="interest-chip">{{ $interet->nom }}</span>
                    @endforeach
                </div>
            @else
                <p class="empty-copy">Aucun int&eacute;r&ecirc;t s&eacute;lectionn&eacute; pour le moment.</p>
            @endif
            <a href="{{ route('interets.index') }}" class="btn-manage">Ajouter ou retirer mes int&eacute;r&ecirc;ts</a>
        </div>

        <div class="card profile-panel">
            <div class="side-title">Taxonomie fournie</div>
            <ul class="taxonomy-list">
                @foreach ($interetsParCategorie as $categorie => $interets)
                    <li><strong>{{ $categorie }}</strong><br>{{ $interets->pluck('nom')->join(', ') }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const bioField = document.getElementById('bioField');
    const bioCount = document.getElementById('bioCount');
    bioField?.addEventListener('input', function () { bioCount.textContent = this.value.length; });
</script>
@endsection
