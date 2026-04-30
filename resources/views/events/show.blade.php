@extends('layouts.app')

@section('title', $event->titre)

@section('styles')
<style>
    .event-layout { display: grid; grid-template-columns: minmax(0, 1fr) 280px; gap: 1.5rem; max-width: 960px; margin: 0 auto; }
    .event-main { display: flex; flex-direction: column; gap: 1rem; }
    .event-hero { padding: 1.75rem; }
    .event-hero-title { color: #fff; font-size: 1.6rem; font-weight: 700; margin-bottom: 0.75rem; line-height: 1.3; }
    .event-pills { display: flex; flex-wrap: wrap; gap: 0.45rem; margin-bottom: 1rem; }
    .event-pill { display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.25rem 0.7rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; }
    .pill-public  { background: rgba(46,204,113,0.2);  color: #2ecc71; }
    .pill-request { background: rgba(241,196,15,0.2);  color: #f1c40f; }
    .pill-private { background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.6); }
    .pill-actif   { background: rgba(46,204,113,0.15); color: #2ecc71; }
    .pill-annule  { background: rgba(231,76,60,0.2);   color: #e74c3c; }
    .pill-complet { background: rgba(155,89,182,0.2);  color: #9b59b6; }
    .meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
    .meta-item { display: flex; align-items: flex-start; gap: 0.6rem; }
    .meta-icon { width: 36px; height: 36px; border-radius: 10px; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .meta-icon svg { width: 18px; height: 18px; fill: rgba(255,255,255,0.75); }
    .meta-label { color: rgba(255,255,255,0.5); font-size: 0.72rem; font-weight: 600; text-transform: uppercase; }
    .meta-value { color: #fff; font-size: 0.88rem; font-weight: 500; }
    .event-description { padding: 1.25rem; }
    .event-description h3 { color: rgba(255,255,255,0.7); font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.75rem; }
    .event-description p { color: rgba(255,255,255,0.8); font-size: 0.9rem; line-height: 1.65; white-space: pre-line; }

    /* Sidebar */
    .event-sidebar { display: flex; flex-direction: column; gap: 1rem; }
    .sidebar-card { background: rgba(255,255,255,0.1); backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.18); border-radius: 16px; padding: 1.25rem; }
    .sidebar-title { color: rgba(255,255,255,0.7); font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 1rem; }
    .sidebar-card-danger { background: linear-gradient(180deg, rgba(120,18,24,0.72), rgba(70,10,16,0.9)); border: 1px solid rgba(255,132,132,0.38); box-shadow: inset 0 1px 0 rgba(255,255,255,0.06), 0 16px 32px rgba(30,4,8,0.28); }
    .sidebar-card-danger .sidebar-title { color: #ffd6d6; }
    .danger-copy { color: rgba(255,226,226,0.82); font-size: 0.78rem; line-height: 1.5; margin: -0.15rem 0 0.85rem; }

    /* Action buttons */
    .btn-join { width: 100%; border: none; border-radius: 999px; padding: 0.85rem; font-family: 'Poppins', sans-serif; font-size: 0.9rem; font-weight: 700; cursor: pointer; }
    .btn-join-public  { background: #fff; color: #c0392b; }
    .btn-join-request { background: rgba(241,196,15,0.25); color: #f1c40f; border: 1.5px solid rgba(241,196,15,0.4); }
    .btn-cancel { background: transparent; color: rgba(231,76,60,0.8); border: 1px solid rgba(231,76,60,0.4); width: 100%; border-radius: 999px; padding: 0.7rem; font-family: 'Poppins', sans-serif; font-size: 0.85rem; font-weight: 600; cursor: pointer; }
    .btn-cancel:hover { background: rgba(231,76,60,0.1); }
    .pending-badge { text-align: center; padding: 0.75rem; border-radius: 12px; background: rgba(241,196,15,0.15); border: 1.5px solid rgba(241,196,15,0.3); color: #f1c40f; font-size: 0.85rem; font-weight: 600; }
    .confirmed-badge { text-align: center; padding: 0.75rem; border-radius: 12px; background: rgba(46,204,113,0.15); border: 1.5px solid rgba(46,204,113,0.3); color: #2ecc71; font-size: 0.85rem; font-weight: 600; }
    .full-badge { text-align: center; padding: 0.75rem; border-radius: 12px; background: rgba(155,89,182,0.15); color: rgba(255,255,255,0.6); font-size: 0.85rem; }

    /* Participants list */
    .participant-row { display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.6rem; }
    .participant-avatar { width: 32px; height: 32px; border-radius: 50%; background: rgba(255,255,255,0.2); overflow: hidden; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .participant-avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
    .participant-avatar svg { width: 15px; height: 15px; fill: rgba(255,255,255,0.7); }
    .participant-name { color: #fff; font-size: 0.82rem; font-weight: 600; flex: 1; }

    /* Creator controls */
    .creator-actions { display: flex; flex-direction: column; gap: 0.5rem; }
    .btn-respond { border: none; border-radius: 999px; padding: 0.25rem 0.65rem; font-family: 'Poppins', sans-serif; font-size: 0.68rem; font-weight: 700; cursor: pointer; }
    .btn-accept { background: rgba(46,204,113,0.2); color: #2ecc71; }
    .btn-refuse { background: rgba(231,76,60,0.2); color: #e74c3c; }
    .btn-annuler-event { width: 100%; border: 1px solid rgba(255,196,196,0.22); background: rgba(255,255,255,0.08); color: #ffe1e1; border-radius: 999px; padding: 0.72rem; font-family: 'Poppins', sans-serif; font-size: 0.8rem; font-weight: 700; cursor: pointer; }
    .btn-annuler-event:hover { background: rgba(255,255,255,0.12); }
    .manage-form { display: grid; gap: 0.65rem; }
    .manage-form input, .manage-form textarea, .manage-form select { width: 100%; padding: 0.7rem 0.9rem; border-radius: 14px; border: 1px solid rgba(255,255,255,0.18); background: rgba(255,255,255,0.12); color: #fff; font-family: 'Poppins', sans-serif; outline: none; font-size: 0.85rem; }
    .manage-form textarea { resize: vertical; min-height: 84px; }
    .manage-form select option { color: #222; }
    .manage-form button { border: none; border-radius: 999px; padding: 0.75rem 1rem; font-weight: 700; cursor: pointer; font-family: 'Poppins', sans-serif; }
    .btn-save-event { background: #fff; color: #c0392b; }
    .btn-delete-event { width: 100%; border: 1px solid rgba(255,255,255,0.08); background: linear-gradient(180deg, #ff7b72, #e74c3c); color: #fff; box-shadow: 0 10px 22px rgba(231,76,60,0.28); }
    .btn-delete-event:hover { background: linear-gradient(180deg, #ff8a81, #ef5f4f); box-shadow: 0 14px 28px rgba(231,76,60,0.34); transform: translateY(-1px); }
    .access-options { display: grid; gap: 0.55rem; }
    .access-option { position: relative; }
    .access-option input { position: absolute; opacity: 0; pointer-events: none; }
    .access-card { display: flex; flex-direction: column; gap: 0.2rem; padding: 0.8rem 0.9rem; border-radius: 14px; border: 1px solid rgba(255,255,255,0.14); background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.72); cursor: pointer; transition: all 0.2s ease; }
    .access-card strong { color: #fff; font-size: 0.82rem; }
    .access-card span { font-size: 0.7rem; line-height: 1.35; }
    .access-option input:checked + .access-card { background: rgba(255,255,255,0.14); color: rgba(255,255,255,0.9); box-shadow: inset 0 0 0 1px rgba(255,255,255,0.08); }
    .access-option.public input:checked + .access-card { border-color: rgba(46,204,113,0.5); background: rgba(46,204,113,0.16); }
    .access-option.request input:checked + .access-card { border-color: rgba(241,196,15,0.5); background: rgba(241,196,15,0.16); }
    .access-option.private input:checked + .access-card { border-color: rgba(255,255,255,0.35); background: rgba(255,255,255,0.11); }
    .btn-back { color: rgba(255,255,255,0.7); text-decoration: none; display: inline-flex; align-items: center; gap: 0.3rem; font-size: 0.82rem; margin-bottom: 1rem; }
    .btn-back svg { width: 18px; height: 18px; fill: currentColor; }
    .organizer-row { display: flex; align-items: center; gap: 0.75rem; }
    .organizer-avatar { width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.2); overflow: hidden; display: flex; align-items: center; justify-content: center; }
    .organizer-avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
    .organizer-avatar svg { width: 20px; height: 20px; fill: rgba(255,255,255,0.7); }
    @media (max-width: 768px) { .event-layout { grid-template-columns: 1fr; } .meta-grid { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<a href="{{ route('events.index') }}" class="btn-back">
    <svg viewBox="0 0 24 24"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
    {{ __('app.events') }}
</a>

<div class="event-layout">
    {{-- Main content --}}
    <div class="event-main">
        <div class="card event-hero">
            <div class="event-hero-title">{{ $event->titre }}</div>
            <div class="event-pills">
                @php
                    $accessClass = ['public' => 'pill-public', 'sur_demande' => 'pill-request', 'prive' => 'pill-private'][$event->type_acces];
                    $accessLabel = ['public' => __('app.event_public'), 'sur_demande' => __('app.event_on_request'), 'prive' => __('app.event_private')][$event->type_acces];
                @endphp
                <span class="event-pill {{ $accessClass }}">{{ $accessLabel }}</span>
                <span class="event-pill pill-{{ $event->statut }}">
                    {{ ['actif' => __('app.event_status_active'), 'annule' => __('app.event_status_cancelled'), 'complet' => __('app.event_full')][$event->statut] }}
                </span>
            </div>

            <div class="meta-grid">
                <div class="meta-item">
                    <div class="meta-icon"><svg viewBox="0 0 24 24"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg></div>
                    <div>
                        <div class="meta-label">{{ __('app.event_date') }}</div>
                        <div class="meta-value">{{ $event->date_evenement->translatedFormat('l j F Y') }}</div>
                    </div>
                </div>
                <div class="meta-item">
                    <div class="meta-icon"><svg viewBox="0 0 24 24"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67V7z"/></svg></div>
                    <div>
                        <div class="meta-label">{{ __('app.event_time') }}</div>
                        <div class="meta-value">{{ \Carbon\Carbon::parse($event->heure_debut)->format('H:i') }}</div>
                    </div>
                </div>
                @if($event->lieu)
                    <div class="meta-item">
                        <div class="meta-icon"><svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg></div>
                        <div>
                            <div class="meta-label">{{ __('app.event_location') }}</div>
                            <div class="meta-value">{{ $event->lieu }}</div>
                        </div>
                    </div>
                @endif
                <div class="meta-item">
                    <div class="meta-icon"><svg viewBox="0 0 24 24"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg></div>
                    <div>
                        <div class="meta-label">{{ __('app.event_price') }}</div>
                        <div class="meta-value">{{ $event->prix > 0 ? number_format($event->prix, 2) . ' $' : __('app.event_free') }}</div>
                    </div>
                </div>
                <div class="meta-item">
                    <div class="meta-icon"><svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg></div>
                    <div>
                        <div class="meta-label">{{ __('app.event_participants') }}</div>
                        <div class="meta-value">
                            {{ $event->confirmedParticipants->count() }}
                            @if($event->max_participants)
                                / {{ $event->max_participants }}
                                @php $left = $event->max_participants - $event->confirmedParticipants->count(); @endphp
                                @if($left > 0) <span style="color:rgba(255,255,255,0.5);font-size:0.78rem;">({{ $left }} {{ __('app.event_spots_left') }})</span> @endif
                            @endif
                        </div>
                    </div>
                </div>
                @if($event->group)
                    <div class="meta-item">
                        <div class="meta-icon"><svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg></div>
                        <div>
                            <div class="meta-label">{{ __('app.event_group') }}</div>
                            <div class="meta-value">{{ $event->group->nom }}</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @if($event->description)
            <div class="card event-description">
                <h3>{{ __('app.event_description') }}</h3>
                <p>{{ $event->description }}</p>
            </div>
        @endif

        {{-- Confirmed participants --}}
        @if($event->confirmedParticipants->isNotEmpty())
            <div class="card event-description">
                <h3>{{ __('app.event_participants') }} ({{ $event->confirmedParticipants->count() }})</h3>
                @foreach($event->confirmedParticipants as $participant)
                    <div class="participant-row">
                        <div class="participant-avatar">
                            @if($participant->avatar_url)
                                <img src="{{ asset('storage/' . $participant->avatar_url) }}" alt="">
                            @else
                                <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                            @endif
                        </div>
                        <span class="participant-name">{{ $participant->prenom }} {{ $participant->nom }}</span>
                        @if($participant->id === $event->creator_id)
                            <span style="color:rgba(255,255,255,0.5);font-size:0.7rem;">{{ __('app.event_organizer_badge') }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Pending requests (creator only) --}}
        @if($isCreator && $event->pendingParticipants->isNotEmpty())
            <div class="card event-description">
                <h3>{{ __('app.event_pending_requests') }} ({{ $event->pendingParticipants->count() }})</h3>
                @foreach($event->pendingParticipants as $participant)
                    <div class="participant-row">
                        <div class="participant-avatar">
                            @if($participant->avatar_url)
                                <img src="{{ asset('storage/' . $participant->avatar_url) }}" alt="">
                            @else
                                <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                            @endif
                        </div>
                        <span class="participant-name">{{ $participant->prenom }} {{ $participant->nom }}</span>
                        <div style="display:flex;gap:0.35rem;">
                            <form method="POST" action="{{ route('events.respond', [$event, $participant->id]) }}">
                                @csrf <input type="hidden" name="action" value="accept">
                                <button type="submit" class="btn-respond btn-accept">{{ __('app.accept') }}</button>
                            </form>
                            <form method="POST" action="{{ route('events.respond', [$event, $participant->id]) }}">
                                @csrf <input type="hidden" name="action" value="refuse">
                                <button type="submit" class="btn-respond btn-refuse">{{ __('app.decline') }}</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Sidebar --}}
    <aside class="event-sidebar">
        {{-- Organizer --}}
        <div class="sidebar-card">
            <div class="sidebar-title">{{ __('app.event_organizer') }}</div>
            <div class="organizer-row">
                <div class="organizer-avatar">
                    @if($event->creator->avatar_url)
                        <img src="{{ asset('storage/' . $event->creator->avatar_url) }}" alt="">
                    @else
                        <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                    @endif
                </div>
                <div>
                    <div style="color:#fff;font-weight:600;font-size:0.88rem;">{{ $event->creator->prenom }} {{ $event->creator->nom }}</div>
                    @if($event->group) <div style="color:rgba(255,255,255,0.5);font-size:0.75rem;">{{ __('app.via') }} {{ $event->group->nom }}</div> @endif
                </div>
            </div>
        </div>

        {{-- Action card --}}
        @if($event->statut === 'actif')
            <div class="sidebar-card">
                @if($isCreator)
                    <div class="sidebar-title">{{ __('app.event_management') }}</div>
                    <div class="creator-actions">
                        <div class="confirmed-badge">
                            ✓ {{ __('app.event_you_organize') }}
                        </div>
                        <form method="POST" action="{{ route('events.update', $event) }}" class="manage-form">
                            @csrf
                            @method('PUT')
                            <input type="text" name="titre" value="{{ old('titre', $event->titre) }}" required placeholder="{{ __('app.event_title') }}">
                            <textarea name="description" placeholder="{{ __('app.event_description') }}">{{ old('description', $event->description) }}</textarea>
                            <input type="date" name="date_evenement" value="{{ old('date_evenement', $event->date_evenement->format('Y-m-d')) }}" required>
                            <input type="time" name="heure_debut" value="{{ old('heure_debut', \Carbon\Carbon::parse($event->heure_debut)->format('H:i')) }}" required>
                            <input type="text" name="lieu" value="{{ old('lieu', $event->lieu) }}" placeholder="{{ __('app.event_location') }}">
                            <input type="number" name="max_participants" min="2" value="{{ old('max_participants', $event->max_participants) }}" placeholder="{{ __('app.event_max') }}">
                            <input type="number" name="prix" min="0" step="0.01" value="{{ old('prix', $event->prix) }}" placeholder="{{ __('app.event_price') }}">
                            <div class="access-options">
                                <label class="access-option public">
                                    <input type="radio" name="type_acces" value="public" {{ old('type_acces', $event->type_acces) === 'public' ? 'checked' : '' }} required>
                                    <span class="access-card">
                                        <strong>{{ __('app.event_public') }}</strong>
                                        <span>{{ __('app.event_public_desc') }}</span>
                                    </span>
                                </label>
                                <label class="access-option request">
                                    <input type="radio" name="type_acces" value="sur_demande" {{ old('type_acces', $event->type_acces) === 'sur_demande' ? 'checked' : '' }}>
                                    <span class="access-card">
                                        <strong>{{ __('app.event_on_request') }}</strong>
                                        <span>{{ __('app.event_on_request_desc') }}</span>
                                    </span>
                                </label>
                                <label class="access-option private">
                                    <input type="radio" name="type_acces" value="prive" {{ old('type_acces', $event->type_acces) === 'prive' ? 'checked' : '' }}>
                                    <span class="access-card">
                                        <strong>{{ __('app.event_private') }}</strong>
                                        <span>{{ __('app.event_private_desc') }}</span>
                                    </span>
                                </label>
                            </div>
                            <select name="group_id">
                                <option value="">{{ __('app.event_personal') }}</option>
                                @foreach($myGroups as $group)
                                    <option value="{{ $group->id }}" {{ (string) old('group_id', $event->group_id) === (string) $group->id ? 'selected' : '' }}>{{ $group->nom }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn-save-event">{{ __('app.save') }}</button>
                        </form>
                    </div>
                @elseif($myStatus === 'confirme')
                    <div class="sidebar-title">{{ __('app.event_my_participation') }}</div>
                    <div class="confirmed-badge" style="margin-bottom:0.75rem;">✓ {{ __('app.event_you_participate') }}</div>
                    <form method="POST" action="{{ route('events.cancel-join', $event) }}">
                        @csrf
                        <button type="submit" class="btn-cancel">{{ __('app.event_cancel') }}</button>
                    </form>
                @elseif($myStatus === 'en_attente')
                    <div class="pending-badge">⏳ {{ __('app.event_pending') }}</div>
                    <form method="POST" action="{{ route('events.cancel-join', $event) }}" style="margin-top:0.75rem;">
                        @csrf
                        <button type="submit" class="btn-cancel">{{ __('app.event_cancel_request') }}</button>
                    </form>
                @elseif($event->type_acces !== 'prive')
                    <div class="sidebar-title">{{ __('app.event_join_section') }}</div>
                    @if($event->isFull())
                        <div class="full-badge">{{ __('app.event_is_full_msg') }}</div>
                    @else
                        <form method="POST" action="{{ route('events.join', $event) }}">
                            @csrf
                            @if($event->type_acces === 'public')
                                <button type="submit" class="btn-join btn-join-public">{{ __('app.event_join') }}</button>
                            @else
                                <button type="submit" class="btn-join btn-join-request">{{ __('app.event_request') }}</button>
                            @endif
                        </form>
                        @if($event->prix > 0)
                            <p style="color:rgba(255,255,255,0.5);font-size:0.75rem;margin-top:0.5rem;text-align:center;">
                                {{ __('app.event_registration_fee') }} {{ number_format($event->prix, 2) }} $
                            </p>
                        @endif
                    @endif
                @endif
            </div>
            @if($isCreator)
                <div class="sidebar-card sidebar-card-danger">
                    <div class="sidebar-title">{{ __('app.danger_zone') }}</div>
                    <p class="danger-copy">{{ __('app.event_danger_warning') }}</p>
                    <div class="creator-actions">
                        <form method="POST" action="{{ route('events.cancel', $event) }}"
                              onsubmit="return confirm('{{ __('app.event_cancel_confirm') }}')">
                            @csrf
                            <button type="submit" class="btn-annuler-event">{{ __('app.event_cancel_event') }}</button>
                        </form>
                        <form method="POST" action="{{ route('events.destroy', $event) }}"
                              onsubmit="return confirm('Supprimer cet evenement ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete-event">{{ __('app.delete') }}</button>
                        </form>
                    </div>
                </div>
            @endif
        @elseif($event->statut === 'annule')
            <div class="sidebar-card">
                <div style="text-align:center;color:rgba(231,76,60,0.8);font-weight:600;">{{ __('app.event_cancelled_msg') }}</div>
            </div>
        @elseif($event->statut === 'complet')
            <div class="sidebar-card">
                <div class="full-badge" style="text-align:center;">{{ __('app.event_is_full_msg') }}</div>
            </div>
        @endif
    </aside>
</div>
@endsection
