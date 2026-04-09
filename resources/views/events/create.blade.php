@extends('layouts.app')

@section('title', __('app.event_create'))

@section('styles')
<style>
    .create-event-page { max-width: 640px; margin: 0 auto; }
    .create-event-card { padding: 2rem; }
    .create-event-card h1 { color: #fff; font-size: 1.4rem; font-weight: 700; margin-bottom: 1.5rem; }
    .form-grid { display: grid; gap: 1rem; }
    .form-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
    label { display: block; color: rgba(255,255,255,0.85); font-size: 0.82rem; font-weight: 600; margin-bottom: 0.35rem; }
    input[type="text"], input[type="date"], input[type="time"], input[type="number"],
    textarea, select {
        width: 100%; padding: 0.85rem 1rem; border: 1px solid rgba(255,255,255,0.24);
        border-radius: 18px; background: rgba(255,255,255,0.12); color: #fff;
        outline: none; font-family: 'Poppins', sans-serif; font-size: 0.88rem;
    }
    input::placeholder, textarea::placeholder { color: rgba(255,255,255,0.5); }
    select option { color: #222; background: #fff; }
    textarea { resize: vertical; min-height: 100px; }
    .btn-submit { width: 100%; border: none; border-radius: 999px; padding: 0.9rem; background: #fff; color: #c0392b; font-weight: 700; font-size: 0.95rem; font-family: 'Poppins', sans-serif; cursor: pointer; margin-top: 0.5rem; }
    .btn-back-link { display: inline-flex; align-items: center; gap: 0.3rem; color: rgba(255,255,255,0.7); text-decoration: none; font-size: 0.82rem; margin-bottom: 1rem; }
    .btn-back-link svg { width: 18px; height: 18px; fill: currentColor; }
    .error-message { background: rgba(231,76,60,0.18); color: #fff; padding: 0.75rem 1rem; border-radius: 12px; border: 1px solid rgba(231,76,60,0.35); margin-bottom: 1rem; font-size: 0.85rem; }
    .help-text { color: rgba(255,255,255,0.5); font-size: 0.74rem; margin-top: 0.3rem; }
    .access-options { display: flex; flex-direction: column; gap: 0.5rem; }
    .access-option { display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 14px; background: rgba(255,255,255,0.08); cursor: pointer; border: 1.5px solid transparent; transition: all 0.2s; }
    .access-option:has(input:checked) { background: rgba(255,255,255,0.15); border-color: rgba(255,255,255,0.35); }
    .access-option input { margin-top: 0.2rem; flex-shrink: 0; }
    .access-option-label { color: #fff; font-size: 0.85rem; font-weight: 600; }
    .access-option-desc { color: rgba(255,255,255,0.55); font-size: 0.75rem; }
    @media (max-width: 580px) { .form-row-2 { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<div class="create-event-page">
    <a href="{{ route('events.index') }}" class="btn-back-link">
        <svg viewBox="0 0 24 24"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
        Retour
    </a>

    <div class="card create-event-card">
        <h1>
            <svg viewBox="0 0 24 24" style="width:22px;height:22px;fill:rgba(255,255,255,0.8);vertical-align:middle;margin-right:0.5rem;"><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zm-7-9h-2v3H7v2h3v3h2v-3h3v-2h-3z"/></svg>
            {{ __('app.event_create') }}
        </h1>

        @if ($errors->any())
            <div class="error-message">
                @foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('events.store') }}" class="form-grid">
            @csrf

            <div>
                <label>{{ __('app.event_title') }} *</label>
                <input type="text" name="titre" value="{{ old('titre') }}" maxlength="100" required placeholder="Nom de l'événement">
            </div>

            <div>
                <label>{{ __('app.event_description') }}</label>
                <textarea name="description" maxlength="2000" placeholder="Décris ton événement, ce qui est prévu, ce qu'il faut apporter…">{{ old('description') }}</textarea>
            </div>

            <div class="form-row-2">
                <div>
                    <label>{{ __('app.event_date') }} *</label>
                    <input type="date" name="date_evenement" value="{{ old('date_evenement', now()->toDateString()) }}" required min="{{ now()->toDateString() }}">
                </div>
                <div>
                    <label>{{ __('app.event_time') }} *</label>
                    <input type="time" name="heure_debut" value="{{ old('heure_debut', '18:00') }}" required>
                </div>
            </div>

            <div>
                <label>{{ __('app.event_location') }}</label>
                <input type="text" name="lieu" value="{{ old('lieu') }}" maxlength="200" placeholder="Ex. Salle A-201, Campus, Parc Laurier…">
            </div>

            <div class="form-row-2">
                <div>
                    <label>{{ __('app.event_max') }}</label>
                    <input type="number" name="max_participants" value="{{ old('max_participants') }}" min="2" placeholder="Illimité">
                    <div class="help-text">Laisse vide pour illimité</div>
                </div>
                <div>
                    <label>{{ __('app.event_price') }}</label>
                    <input type="number" name="prix" value="{{ old('prix', '0') }}" min="0" step="0.01" placeholder="0.00">
                    <div class="help-text">0 = gratuit</div>
                </div>
            </div>

            @if($myGroups->isNotEmpty())
                <div>
                    <label>{{ __('app.event_group') }} <span style="color:rgba(255,255,255,0.5);font-weight:400;">(optionnel)</span></label>
                    <select name="group_id">
                        <option value="">— Événement personnel —</option>
                        @foreach($myGroups as $group)
                            <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>{{ $group->nom }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div>
                <label>{{ __('app.event_access') }} *</label>
                <div class="access-options">
                    <label class="access-option">
                        <input type="radio" name="type_acces" value="public" {{ old('type_acces', 'public') === 'public' ? 'checked' : '' }}>
                        <div>
                            <div class="access-option-label">🌐 {{ __('app.event_public') }}</div>
                            <div class="access-option-desc">N'importe qui peut rejoindre directement.</div>
                        </div>
                    </label>
                    <label class="access-option">
                        <input type="radio" name="type_acces" value="sur_demande" {{ old('type_acces') === 'sur_demande' ? 'checked' : '' }}>
                        <div>
                            <div class="access-option-label">📋 {{ __('app.event_on_request') }}</div>
                            <div class="access-option-desc">Visible, mais les participants doivent être approuvés.</div>
                        </div>
                    </label>
                    <label class="access-option">
                        <input type="radio" name="type_acces" value="prive" {{ old('type_acces') === 'prive' ? 'checked' : '' }}>
                        <div>
                            <div class="access-option-label">🔒 {{ __('app.event_private') }}</div>
                            <div class="access-option-desc">Seulement visible et accessible aux invités.</div>
                        </div>
                    </label>
                </div>
            </div>

            <button type="submit" class="btn-submit">Créer l'événement</button>
        </form>
    </div>
</div>
@endsection
