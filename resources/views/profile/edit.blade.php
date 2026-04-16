@extends('layouts.app')

@section('title', 'Mon profil')

@section('styles')
<style>
    .profile-page { display: grid; grid-template-columns: 1.15fr 0.85fr; gap: 1.5rem; align-items: start; }
    .profile-panel { padding: 1.5rem; }
    .profile-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; }
    .avatar-large { width: 110px; height: 110px; border-radius: 50%; background: rgba(255,255,255,0.25); display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; }
    .avatar-large svg { width: 50px; height: 50px; fill: rgba(255,255,255,0.85); }
    .profile-header h1 { color: #fff; font-size: 1.5rem; font-weight: 700; margin-bottom: 0.35rem; }
    .profile-header p, .empty-copy { color: rgba(255,255,255,0.74); font-size: 0.9rem; line-height: 1.5; }
    .profile-form { display: grid; gap: 1rem; }
    .profile-form label, .side-title { display: block; color: rgba(255,255,255,0.85); font-size: 0.82rem; font-weight: 600; margin-bottom: 0.35rem; }
    .profile-form input, .profile-form textarea, .profile-form select { width: 100%; padding: 0.85rem 1rem; border: 1px solid rgba(255,255,255,0.24); border-radius: 18px; background: rgba(255,255,255,0.12); color: #fff; outline: none; font-family: 'Poppins', sans-serif; }
    .profile-form input[type="file"] { padding: 0.75rem 1rem; }
    .profile-form select option { color: #222; }
    .profile-form textarea { resize: vertical; min-height: 110px; }
    .profile-form input::placeholder, .profile-form textarea::placeholder { color: rgba(255,255,255,0.55); }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
    .email-display { padding: 0.85rem 1rem; border-radius: 18px; background: rgba(255,255,255,0.08); color: rgba(255,255,255,0.76); }
    .btn-save, .btn-manage { width: 100%; border: none; border-radius: 999px; padding: 0.9rem 1rem; font-weight: 700; cursor: pointer; text-decoration: none; text-align: center; font-family: 'Poppins', sans-serif; }
    .btn-save { background: #fff; color: #c0392b; }
    .btn-manage { display: inline-flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.14); color: #fff; margin-top: 1rem; }
    .success-message { background: rgba(46,204,113,0.16); color: #fff; padding: 0.75rem 1rem; border-radius: 12px; border: 1px solid rgba(46,204,113,0.35); margin-bottom: 1rem; }
    .error-message { background: rgba(231,76,60,0.18); color: #fff; padding: 0.75rem 1rem; border-radius: 12px; border: 1px solid rgba(231,76,60,0.35); margin-bottom: 1rem; }
    .char-count { text-align: right; color: rgba(255,255,255,0.6); font-size: 0.74rem; margin-top: 0.35rem; }
    .field-note { color: rgba(255,255,255,0.6); font-size: 0.76rem; line-height: 1.5; margin-top: -0.35rem; }
    .side-stack { display: grid; gap: 1.5rem; }
    /* Removable chips */
    .interest-chips { display: flex; flex-wrap: wrap; gap: 0.5rem; }
    .interest-chip { display: inline-flex; align-items: center; gap: 0.32rem; padding: 0.38rem 0.5rem 0.38rem 0.78rem; border-radius: 999px; background: rgba(255,255,255,0.18); color: #fff; font-size: 0.78rem; }
    .chip-rm-btn { display: inline-flex; align-items: center; justify-content: center; width: 18px; height: 18px; border-radius: 50%; background: rgba(255,255,255,0.18); border: none; color: rgba(255,255,255,0.8); cursor: pointer; font-size: 0.68rem; padding: 0; line-height: 1; transition: background 0.15s; flex-shrink: 0; }
    .chip-rm-btn:hover { background: rgba(231,76,60,0.65); color: #fff; }

    /* Interest picker */
    .btn-add-interests { width: 100%; margin-top: 0.9rem; border: 1px dashed rgba(255,255,255,0.3); border-radius: 999px; padding: 0.7rem 1rem; background: transparent; color: rgba(255,255,255,0.75); font-family: 'Poppins', sans-serif; font-size: 0.82rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.4rem; transition: all 0.2s; }
    .btn-add-interests:hover { background: rgba(255,255,255,0.08); color: #fff; border-color: rgba(255,255,255,0.5); }
    .interest-picker { margin-top: 0.9rem; display: none; }
    .interest-picker.open { display: block; }
    .picker-search { width: 100%; box-sizing: border-box; padding: 0.7rem 0.9rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.2); background: rgba(255,255,255,0.1); color: #fff; outline: none; font-family: 'Poppins', sans-serif; font-size: 0.82rem; margin-bottom: 0.75rem; }
    .picker-search::placeholder { color: rgba(255,255,255,0.4); }
    .picker-cat { border-radius: 12px; background: rgba(255,255,255,0.06); overflow: hidden; margin-bottom: 0.5rem; }
    .picker-cat:last-child { margin-bottom: 0; }
    .picker-cat-toggle { width: 100%; display: flex; align-items: center; justify-content: space-between; padding: 0.65rem 0.85rem; background: none; border: none; color: #fff; font-family: 'Poppins', sans-serif; font-size: 0.8rem; font-weight: 600; cursor: pointer; }
    .picker-cat-toggle svg { width: 14px; height: 14px; fill: currentColor; transition: transform 0.2s; flex-shrink: 0; }
    .picker-cat.open .picker-cat-toggle svg { transform: rotate(180deg); }
    .picker-cat-body { display: none; padding: 0 0.75rem 0.75rem; }
    .picker-cat.open .picker-cat-body { display: block; }
    .picker-items { display: flex; flex-wrap: wrap; gap: 0.4rem; }
    .picker-item { display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.32rem 0.65rem; border-radius: 999px; border: 1.5px solid rgba(255,255,255,0.2); background: rgba(255,255,255,0.08); color: rgba(255,255,255,0.8); font-size: 0.74rem; cursor: pointer; transition: all 0.15s; user-select: none; }
    .picker-item:hover { background: rgba(255,255,255,0.16); color: #fff; }
    .picker-item.selected { background: rgba(46,204,113,0.22); color: #2ecc71; border-color: rgba(46,204,113,0.5); font-weight: 600; }
    .picker-item.item-hidden { display: none; }
    .section-label { color: rgba(255,255,255,0.55); font-size: 0.74rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.65rem; margin-top: 0.25rem; }
    .connexion-options { display: flex; flex-wrap: wrap; gap: 0.55rem; }
    .connexion-option { position: relative; }
    .connexion-option input { position: absolute; opacity: 0; }
    .connexion-option span { display: inline-flex; align-items: center; padding: 0.5rem 0.9rem; border-radius: 999px; background: rgba(255,255,255,0.12); color: rgba(255,255,255,0.82); font-size: 0.82rem; cursor: pointer; border: 1.5px solid transparent; transition: all 0.2s; }
    .connexion-option input:checked + span { background: rgba(255,255,255,0.22); color: #fff; border-color: rgba(255,255,255,0.5); font-weight: 600; }
    /* Dark mode toggle */
    .toggle-row { display: flex; align-items: center; justify-content: space-between; padding: 0.65rem 0; border-bottom: 1px solid rgba(255,255,255,0.1); }
    .toggle-row:last-child { border-bottom: none; }
    .toggle-label { color: rgba(255,255,255,0.85); font-size: 0.88rem; font-weight: 500; }
    .toggle-switch { position: relative; width: 46px; height: 25px; }
    .toggle-switch input { opacity: 0; width: 0; height: 0; }
    .toggle-slider { position: absolute; inset: 0; background: rgba(255,255,255,0.2); border-radius: 25px; cursor: pointer; transition: 0.3s; }
    .toggle-slider:before { content: ''; position: absolute; width: 19px; height: 19px; left: 3px; bottom: 3px; background: #fff; border-radius: 50%; transition: 0.3s; }
    .toggle-switch input:checked + .toggle-slider { background: rgba(46,204,113,0.6); }
    .toggle-switch input:checked + .toggle-slider:before { transform: translateX(21px); }
    /* Language radio */
    .lang-options { display: flex; gap: 0.55rem; }
    .lang-option { position: relative; }
    .lang-option input { position: absolute; opacity: 0; }
    .lang-option span { display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.45rem 0.85rem; border-radius: 999px; background: rgba(255,255,255,0.12); color: rgba(255,255,255,0.82); font-size: 0.82rem; cursor: pointer; border: 1.5px solid transparent; transition: all 0.2s; }
    .lang-option input:checked + span { background: rgba(255,255,255,0.22); color: #fff; border-color: rgba(255,255,255,0.5); font-weight: 600; }
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
                    <img id="avatarPreviewImg" src="" alt="" style="width:100%;height:100%;object-fit:cover;display:none;">
                    <svg id="avatarPreviewSvg" viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                @endif
            </div>
            <div>
                <h1>Mon profil</h1>
                <p>Personnalise ton profil et tes centres d'int&eacute;r&ecirc;t pour trouver des gens avec qui faire des activit&eacute;s.</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="error-message">
                @foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach
            </div>
        @endif

        @if (session('success'))
            <div class="success-message">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" class="profile-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div><label>Pr&eacute;nom</label><input type="text" name="prenom" value="{{ old('prenom', $user->prenom) }}" required></div>
                <div><label>Nom</label><input type="text" name="nom" value="{{ old('nom', $user->nom) }}" required></div>
            </div>
            <div><label>Courriel</label><div class="email-display">{{ $user->email }}</div></div>
            <div><label>No. de programme ou d&eacute;partement</label><input type="text" name="numero_programme" value="{{ old('numero_programme', $user->numero_programme) }}" placeholder="Ex. Techniques de l'informatique"></div>
            <div>
                <label>Bio courte</label>
                <textarea name="bio" id="bioField" maxlength="200" placeholder="Dis quelques mots sur toi, tes passions, ce que tu aimes faire...">{{ old('bio', $user->bio) }}</textarea>
                <div class="char-count"><span id="bioCount">{{ strlen(old('bio', $user->bio ?? '')) }}</span>/200</div>
            </div>
            <div><label>Photo de profil</label><input type="file" name="avatar" id="avatarInput" accept="image/*"></div>

            <div style="border-top: 1px solid rgba(255,255,255,0.12); padding-top: 1rem;">
                <div class="section-label">S&eacute;curit&eacute; du compte</div>
                <div class="form-row">
                    <div>
                        <label>Mot de passe actuel</label>
                        <input type="password" name="current_password" autocomplete="current-password" placeholder="Entre ton mot de passe actuel">
                    </div>
                    <div>
                        <label>Nouveau mot de passe</label>
                        <input type="password" name="password" autocomplete="new-password" placeholder="Minimum 8 caract&egrave;res">
                    </div>
                </div>
                <div>
                    <label>Confirmer le nouveau mot de passe</label>
                    <input type="password" name="password_confirmation" autocomplete="new-password" placeholder="R&eacute;p&egrave;te le nouveau mot de passe">
                </div>
                <div class="field-note">Laisse ces champs vides si tu ne veux pas changer ton mot de passe.</div>
            </div>

            <div>
                <label>Visibilit&eacute;</label>
                <select name="visibilite">
                    <option value="public"  {{ old('visibilite', $user->visibilite) === 'public'  ? 'selected' : '' }}>Public — visible dans les suggestions</option>
                    <option value="prive"   {{ old('visibilite', $user->visibilite) === 'prive'   ? 'selected' : '' }}>Priv&eacute; — non visible dans les suggestions</option>
                </select>
            </div>

            <div>
                <div class="section-label">Je cherche &agrave; &hellip; <span style="font-weight:400;text-transform:none;letter-spacing:0;">(plusieurs choix possibles)</span></div>
                @php $selected = old('type_connexion', $user->type_connexion ?? []); @endphp
                <div class="connexion-options">
                    <label class="connexion-option">
                        <input type="checkbox" name="type_connexion[]" value="amitie" {{ in_array('amitie', $selected) ? 'checked' : '' }}>
                        <span>&#128075; Me faire des amis</span>
                    </label>
                    <label class="connexion-option">
                        <input type="checkbox" name="type_connexion[]" value="activites" {{ in_array('activites', $selected) ? 'checked' : '' }}>
                        <span>&#127939; Faire des activit&eacute;s</span>
                    </label>
                    <label class="connexion-option">
                        <input type="checkbox" name="type_connexion[]" value="etudes" {{ in_array('etudes', $selected) ? 'checked' : '' }}>
                        <span>&#128218; Partenaire d'&eacute;tudes</span>
                    </label>
                    <label class="connexion-option">
                        <input type="checkbox" name="type_connexion[]" value="sorties" {{ in_array('sorties', $selected) ? 'checked' : '' }}>
                        <span>&#127917; Sorties &amp; &eacute;v&eacute;nements</span>
                    </label>
                    <label class="connexion-option">
                        <input type="checkbox" name="type_connexion[]" value="gaming" {{ in_array('gaming', $selected) ? 'checked' : '' }}>
                        <span>&#127918; Gaming ensemble</span>
                    </label>
                </div>
            </div>

            {{-- Settings: dark mode + language --}}
            <div style="border-top: 1px solid rgba(255,255,255,0.12); padding-top: 1rem;">
                <div class="section-label">{{ __('app.settings_dark_mode') }} &amp; {{ __('app.settings_language') }}</div>
                <div class="toggle-row">
                    <span class="toggle-label">
                        <svg viewBox="0 0 24 24" style="width:16px;height:16px;fill:currentColor;margin-right:0.4rem;vertical-align:middle;"><path d="M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9 9-4.03 9-9c0-.46-.04-.92-.1-1.36-.98 1.37-2.58 2.26-4.4 2.26-2.98 0-5.4-2.42-5.4-5.4 0-1.81.89-3.42 2.26-4.4-.44-.06-.9-.1-1.36-.1z"/></svg>
                        {{ __('app.settings_dark_mode') }}
                    </span>
                    <label class="toggle-switch">
                        <input type="checkbox" name="dark_mode" value="1" {{ old('dark_mode', $user->dark_mode) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <div class="toggle-row" style="border-bottom:none;margin-top:0.5rem;">
                    <span class="toggle-label">
                        <svg viewBox="0 0 24 24" style="width:16px;height:16px;fill:currentColor;margin-right:0.4rem;vertical-align:middle;"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zm6.93 6h-2.95c-.32-1.25-.78-2.45-1.38-3.56 1.84.63 3.37 1.91 4.33 3.56zM12 4.04c.83 1.2 1.48 2.53 1.91 3.96h-3.82c.43-1.43 1.08-2.76 1.91-3.96zM4.26 14C4.1 13.36 4 12.69 4 12s.1-1.36.26-2h3.38c-.08.66-.14 1.32-.14 2 0 .68.06 1.34.14 2H4.26zm.82 2h2.95c.32 1.25.78 2.45 1.38 3.56-1.84-.63-3.37-1.9-4.33-3.56zm2.95-8H5.08c.96-1.66 2.49-2.93 4.33-3.56C8.81 5.55 8.35 6.75 8.03 8zM12 19.96c-.83-1.2-1.48-2.53-1.91-3.96h3.82c-.43 1.43-1.08 2.76-1.91 3.96zM14.34 14H9.66c-.09-.66-.16-1.32-.16-2 0-.68.07-1.35.16-2h4.68c.09.65.16 1.32.16 2 0 .68-.07 1.34-.16 2zm.25 5.56c.6-1.11 1.06-2.31 1.38-3.56h2.95c-.96 1.65-2.49 2.93-4.33 3.56zM16.36 14c.08-.66.14-1.32.14-2 0-.68-.06-1.34-.14-2h3.38c.16.64.26 1.31.26 2s-.1 1.36-.26 2h-3.38z"/></svg>
                        {{ __('app.settings_language') }}
                    </span>
                    <div class="lang-options">
                        <label class="lang-option">
                            <input type="radio" name="langue" value="fr" {{ old('langue', $user->langue ?? 'fr') === 'fr' ? 'checked' : '' }}>
                            <span>🇫🇷 {{ __('app.settings_french') }}</span>
                        </label>
                        <label class="lang-option">
                            <input type="radio" name="langue" value="en" {{ old('langue', $user->langue ?? 'fr') === 'en' ? 'checked' : '' }}>
                            <span>🇬🇧 {{ __('app.settings_english') }}</span>
                        </label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-save">{{ __('app.save') }}</button>
        </form>
    </div>

    <div class="side-stack">
        <div class="card profile-panel">
            <div class="side-title" style="display:flex;align-items:center;justify-content:space-between;gap:.5rem;">
                <span>Mes int&eacute;r&ecirc;ts <span id="interetCount" style="font-size:.72rem;font-weight:600;background:rgba(255,255,255,.14);color:rgba(255,255,255,.75);padding:.15rem .5rem;border-radius:999px;">{{ $user->interets->count() }}</span></span>
                <a href="{{ route('dashboard') }}" style="font-size:.72rem;font-weight:600;color:rgba(255,255,255,.65);text-decoration:none;padding:.2rem .6rem;border:1px solid rgba(255,255,255,.2);border-radius:999px;white-space:nowrap;">&#128202; Tableau de bord</a>
            </div>

            @php $userInteretIds = $user->interets->pluck('id')->all(); @endphp

            <div class="interest-chips" id="profileChips">
                @forelse ($user->interets->sortBy(fn ($i) => $i->categorie . '|' . $i->nom) as $interet)
                    <span class="interest-chip" data-id="{{ $interet->id }}">
                        {{ $interet->nom }}
                        <button type="button" class="chip-rm-btn" data-id="{{ $interet->id }}" title="Retirer">✕</button>
                    </span>
                @empty
                    <p class="empty-copy" id="profileEmptyMsg">Aucun int&eacute;r&ecirc;t s&eacute;lectionn&eacute; pour le moment.</p>
                @endforelse
            </div>

            <button type="button" class="btn-add-interests" id="togglePicker">
                <svg viewBox="0 0 24 24" style="width:14px;height:14px;fill:currentColor;"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                Ajouter des int&eacute;r&ecirc;ts
            </button>

            <div class="interest-picker" id="interestPicker">
                <input type="text" class="picker-search" id="pickerSearch" placeholder="Rechercher…" autocomplete="off">
                @foreach ($interetsParCategorie as $categorie => $interets)
                    <div class="picker-cat" data-cat>
                        <button type="button" class="picker-cat-toggle">
                            {{ $categorie }}
                            <svg viewBox="0 0 24 24"><path d="M7 10l5 5 5-5z"/></svg>
                        </button>
                        <div class="picker-cat-body">
                            <div class="picker-items">
                                @foreach ($interets as $interet)
                                    <span class="picker-item {{ in_array($interet->id, $userInteretIds) ? 'selected' : '' }}"
                                          data-id="{{ $interet->id }}"
                                          data-name="{{ mb_strtolower($interet->nom) }}">
                                        {{ $interet->nom }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const bioField = document.getElementById('bioField');
    const bioCount = document.getElementById('bioCount');
    bioField?.addEventListener('input', function () { bioCount.textContent = this.value.length; });

    const avatarInput   = document.getElementById('avatarInput');
    const avatarImg     = document.getElementById('avatarPreviewImg');
    const avatarSvg     = document.getElementById('avatarPreviewSvg');
    avatarInput?.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            avatarImg.src = e.target.result;
            avatarImg.style.display = 'block';
            if (avatarSvg) avatarSvg.style.display = 'none';
        };
        reader.readAsDataURL(file);
    });

    // ── Interest picker (AJAX) ──
    const csrfToken    = document.querySelector('meta[name="csrf-token"]').content;
    const toggleRoute  = '{{ route("interets.toggle") }}';
    const chipsEl      = document.getElementById('profileChips');
    const pickerEl     = document.getElementById('interestPicker');
    const togglePickerBtn = document.getElementById('togglePicker');
    const pickerSearch = document.getElementById('pickerSearch');

    // Open / close picker
    togglePickerBtn?.addEventListener('click', () => {
        pickerEl.classList.toggle('open');
        if (pickerEl.classList.contains('open')) {
            pickerSearch.value = '';
            filterPickerItems('');
            pickerSearch.focus();
        }
    });

    // Collapsible picker categories
    pickerEl?.querySelectorAll('.picker-cat-toggle').forEach(btn => {
        btn.addEventListener('click', () => btn.closest('.picker-cat').classList.toggle('open'));
    });

    // Picker search filter
    pickerSearch?.addEventListener('input', function () {
        filterPickerItems(this.value.trim().toLowerCase());
    });

    function filterPickerItems(q) {
        pickerEl.querySelectorAll('.picker-cat').forEach(cat => {
            const items = cat.querySelectorAll('.picker-item');
            let vis = 0;
            items.forEach(item => {
                const match = q === '' || (item.dataset.name || '').includes(q);
                item.classList.toggle('item-hidden', !match);
                if (match) vis++;
            });
            if (q !== '') cat.classList.toggle('open', vis > 0);
            cat.style.display = vis === 0 && q !== '' ? 'none' : '';
        });
    }

    const fetchHeaders = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken
    };

    // Toggle interest via click on picker item
    pickerEl?.addEventListener('click', e => {
        const item = e.target.closest('.picker-item');
        if (!item) return;
        const interetId = item.dataset.id;

        fetch(toggleRoute, {
            method: 'POST',
            headers: fetchHeaders,
            body: JSON.stringify({ interet_id: interetId })
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'added') {
                item.classList.add('selected');
                addChip(interetId, item.textContent.trim());
            } else {
                item.classList.remove('selected');
                removeChip(interetId);
            }
        });
    });

    // Remove chip via × button on existing chips
    chipsEl?.addEventListener('click', e => {
        const btn = e.target.closest('.chip-rm-btn');
        if (!btn) return;
        const interetId = btn.dataset.id;

        fetch(toggleRoute, {
            method: 'POST',
            headers: fetchHeaders,
            body: JSON.stringify({ interet_id: interetId })
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'removed') {
                removeChip(interetId);
                const pickerItem = pickerEl?.querySelector(`.picker-item[data-id="${interetId}"]`);
                if (pickerItem) pickerItem.classList.remove('selected');
            }
        });
    });

    const countBadge = document.getElementById('interetCount');

    function updateCount(delta) {
        if (!countBadge) return;
        countBadge.textContent = Math.max(0, parseInt(countBadge.textContent || '0') + delta);
    }

    function addChip(id, name) {
        document.getElementById('profileEmptyMsg')?.remove();
        if (chipsEl.querySelector(`.interest-chip[data-id="${id}"]`)) return;
        const chip = document.createElement('span');
        chip.className = 'interest-chip';
        chip.dataset.id = id;
        chip.innerHTML = `${name} <button type="button" class="chip-rm-btn" data-id="${id}" title="Retirer">✕</button>`;
        chipsEl.appendChild(chip);
        updateCount(+1);
    }

    function removeChip(id) {
        const chip = chipsEl.querySelector(`.interest-chip[data-id="${id}"]`);
        if (!chip) return;
        chip.remove();
        updateCount(-1);
        if (!chipsEl.querySelector('.interest-chip')) {
            const p = document.createElement('p');
            p.className = 'empty-copy';
            p.id = 'profileEmptyMsg';
            p.textContent = "Aucun intérêt sélectionné pour le moment.";
            chipsEl.appendChild(p);
        }
    }
</script>
@endsection
