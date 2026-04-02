@extends('layouts.app')

@section('title', 'Mon profil')

@section('styles')
<style>
    .profile-page { display: grid; grid-template-columns: 1.15fr 0.85fr; gap: 1.5rem; align-items: start; }
    .profile-panel { padding: 1.5rem; }
    .profile-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; }
    .avatar-large { width: 110px; height: 110px; border-radius: 50%; background: rgba(255, 255, 255, 0.25); display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; cursor: pointer; position: relative; }
    .avatar-large svg { width: 50px; height: 50px; fill: rgba(255, 255, 255, 0.85); }
    .profile-header h1 { color: #fff; font-size: 1.5rem; font-weight: 700; margin-bottom: 0.35rem; }
    .profile-header p, .empty-copy { color: rgba(255, 255, 255, 0.74); font-size: 0.9rem; line-height: 1.5; }
    .profile-form { display: grid; gap: 1rem; }
    .profile-form label, .side-title { display: block; color: rgba(255, 255, 255, 0.85); font-size: 0.82rem; font-weight: 600; margin-bottom: 0.35rem; }
    .profile-form input, .profile-form textarea, .profile-form select { width: 100%; padding: 0.85rem 1rem; border: 1px solid rgba(255, 255, 255, 0.24); border-radius: 18px; background: rgba(255, 255, 255, 0.12); color: #fff; outline: none; font-family: 'Poppins', sans-serif; }
    .profile-form input[type="file"] { padding: 0.75rem 1rem; }
    .profile-form select option { color: #222; }
    .profile-form textarea { resize: vertical; min-height: 110px; }
    .profile-form input::placeholder, .profile-form textarea::placeholder { color: rgba(255, 255, 255, 0.55); }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
    .email-display { padding: 0.85rem 1rem; border-radius: 18px; background: rgba(255, 255, 255, 0.08); color: rgba(255, 255, 255, 0.76); }
    .btn-save, .btn-manage { width: 100%; border: none; border-radius: 999px; padding: 0.9rem 1rem; font-weight: 700; cursor: pointer; text-decoration: none; text-align: center; font-family: 'Poppins', sans-serif; }
    .btn-save { background: #fff; color: #c0392b; }
    .btn-manage { display: inline-flex; align-items: center; justify-content: center; background: rgba(255, 255, 255, 0.14); color: #fff; margin-top: 1rem; }
    .error-message { background: rgba(231, 76, 60, 0.18); color: #fff; padding: 0.75rem 1rem; border-radius: 12px; border: 1px solid rgba(231, 76, 60, 0.35); }
    .char-count { text-align: right; color: rgba(255, 255, 255, 0.6); font-size: 0.74rem; margin-top: 0.35rem; }
    .side-stack { display: grid; gap: 1.5rem; }
    .interest-chips { display: flex; flex-wrap: wrap; gap: 0.55rem; }
    .interest-chip { padding: 0.45rem 0.8rem; border-radius: 999px; background: rgba(255, 255, 255, 0.18); color: #fff; font-size: 0.78rem; }
    .taxonomy-list { list-style: none; display: grid; gap: 0.85rem; }
    .taxonomy-list li { padding: 0.9rem 1rem; border-radius: 16px; background: rgba(255, 255, 255, 0.08); color: rgba(255, 255, 255, 0.82); font-size: 0.9rem; line-height: 1.5; }
    .section-title { color: #fff; font-size: 1rem; font-weight: 700; margin: 0.5rem 0 0.75rem; border-bottom: 1px solid rgba(255,255,255,0.15); padding-bottom: 0.5rem; }
    .relation-options { display: flex; flex-wrap: wrap; gap: 0.55rem; }
    .relation-option { position: relative; }
    .relation-option input { position: absolute; opacity: 0; }
    .relation-option span { display: inline-flex; align-items: center; padding: 0.5rem 0.9rem; border-radius: 999px; background: rgba(255,255,255,0.12); color: rgba(255,255,255,0.82); font-size: 0.82rem; cursor: pointer; border: 1.5px solid transparent; transition: all 0.2s; }
    .relation-option input:checked + span { background: rgba(255,255,255,0.22); color: #fff; border-color: rgba(255,255,255,0.5); font-weight: 600; }
    .profile-completeness { margin-bottom: 1rem; padding: 0.75rem 1rem; border-radius: 14px; background: rgba(255,255,255,0.08); }
    .profile-completeness p { color: rgba(255,255,255,0.72); font-size: 0.8rem; margin-bottom: 0.4rem; }
    .completeness-bar { height: 6px; border-radius: 99px; background: rgba(255,255,255,0.15); overflow: hidden; }
    .completeness-fill { height: 100%; border-radius: 99px; background: linear-gradient(90deg, #2ecc71, #27ae60); transition: width 0.3s; }
    @media (max-width: 880px) { .profile-page, .form-row { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<div class="profile-page">
    <div class="card profile-panel">
        <div class="profile-header">
            <div class="avatar-large" id="avatarPreview">
                @if ($user->avatar_url)
                    <img id="avatarPreviewImg" src="{{ asset('storage/' . $user->avatar_url) }}" alt="Avatar" style="width:100%;height:100%;object-fit:cover;">
                @else
                    <img id="avatarPreviewImg" src="" alt="Avatar" style="width:100%;height:100%;object-fit:cover;display:none;">
                    <svg id="avatarPreviewSvg" viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                @endif
            </div>
            <div>
                <h1>Mon profil</h1>
                <p>Pr&eacute;nom, nom, programme, bio, avatar, visibilit&eacute;, genre et int&eacute;r&ecirc;ts.</p>
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

            <div class="section-title">Informations g&eacute;n&eacute;rales</div>
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
            <div><label>Avatar</label><input type="file" name="avatar" id="avatarInput" accept="image/*"></div>
            <div>
                <label>Visibilit&eacute;</label>
                <select name="visibilite">
                    <option value="public" {{ old('visibilite', $user->visibilite) === 'public' ? 'selected' : '' }}>Public</option>
                    <option value="prive" {{ old('visibilite', $user->visibilite) === 'prive' ? 'selected' : '' }}>Priv&eacute;</option>
                </select>
            </div>

            <div class="section-title">Identit&eacute; et pr&eacute;f&eacute;rences</div>
            <div class="form-row">
                <div>
                    <label>Genre</label>
                    <select name="genre">
                        <option value="" {{ !old('genre', $user->genre) ? 'selected' : '' }}>Non sp&eacute;cifi&eacute;</option>
                        <option value="homme" {{ old('genre', $user->genre) === 'homme' ? 'selected' : '' }}>Homme</option>
                        <option value="femme" {{ old('genre', $user->genre) === 'femme' ? 'selected' : '' }}>Femme</option>
                        <option value="non-binaire" {{ old('genre', $user->genre) === 'non-binaire' ? 'selected' : '' }}>Non-binaire</option>
                        <option value="autre" {{ old('genre', $user->genre) === 'autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                </div>
                <div>
                    <label>Orientation</label>
                    <select name="orientation">
                        <option value="" {{ !old('orientation', $user->orientation) ? 'selected' : '' }}>Non sp&eacute;cifi&eacute;e</option>
                        <option value="heterosexuel" {{ old('orientation', $user->orientation) === 'heterosexuel' ? 'selected' : '' }}>H&eacute;t&eacute;rosexuel(le)</option>
                        <option value="homosexuel" {{ old('orientation', $user->orientation) === 'homosexuel' ? 'selected' : '' }}>Homosexuel(le)</option>
                        <option value="bisexuel" {{ old('orientation', $user->orientation) === 'bisexuel' ? 'selected' : '' }}>Bisexuel(le)</option>
                        <option value="pansexuel" {{ old('orientation', $user->orientation) === 'pansexuel' ? 'selected' : '' }}>Pansexuel(le)</option>
                        <option value="autre" {{ old('orientation', $user->orientation) === 'autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                </div>
            </div>

            <div>
                <label>Type de relation recherch&eacute;e <span style="color:rgba(255,255,255,0.55);font-weight:400;">(plusieurs choix possibles)</span></label>
                @php $selectedRelations = old('type_relation', $user->type_relation ?? []); @endphp
                <div class="relation-options">
                    <label class="relation-option">
                        <input type="checkbox" name="type_relation[]" value="amitie" {{ in_array('amitie', $selectedRelations) ? 'checked' : '' }}>
                        <span>Amiti&eacute;</span>
                    </label>
                    <label class="relation-option">
                        <input type="checkbox" name="type_relation[]" value="romantique_serieux" {{ in_array('romantique_serieux', $selectedRelations) ? 'checked' : '' }}>
                        <span>Relation s&eacute;rieuse</span>
                    </label>
                    <label class="relation-option">
                        <input type="checkbox" name="type_relation[]" value="romantique_casual" {{ in_array('romantique_casual', $selectedRelations) ? 'checked' : '' }}>
                        <span>Relation casual</span>
                    </label>
                    <label class="relation-option">
                        <input type="checkbox" name="type_relation[]" value="activites" {{ in_array('activites', $selectedRelations) ? 'checked' : '' }}>
                        <span>Partenaire d'activit&eacute;s</span>
                    </label>
                </div>
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

    const avatarInput = document.getElementById('avatarInput');
    const avatarPreviewImg = document.getElementById('avatarPreviewImg');
    const avatarPreviewSvg = document.getElementById('avatarPreviewSvg');
    avatarInput?.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (e) {
            avatarPreviewImg.src = e.target.result;
            avatarPreviewImg.style.display = 'block';
            if (avatarPreviewSvg) avatarPreviewSvg.style.display = 'none';
        };
        reader.readAsDataURL(file);
    });
</script>
@endsection
