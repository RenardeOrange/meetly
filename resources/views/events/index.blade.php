@extends('layouts.app')

@section('title', __('app.events'))

@section('styles')
<style>
    .events-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.75rem; }
    .events-header h1 { color: #fff; font-size: 1.5rem; font-weight: 700; }
    .btn-create { background: #fff; color: #c0392b; border: none; border-radius: 999px; padding: 0.65rem 1.4rem; font-family: 'Poppins', sans-serif; font-size: 0.88rem; font-weight: 700; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; }
    .section-title { color: rgba(255,255,255,0.65); font-size: 0.78rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin: 1.5rem 0 0.75rem; }
    .events-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(290px, 1fr)); gap: 1rem; }
    .event-card { padding: 1.25rem; text-decoration: none; display: flex; flex-direction: column; gap: 0.55rem; }
    .event-card:hover { transform: translateY(-3px); }
    .event-title { color: #fff; font-weight: 700; font-size: 1rem; }
    .event-meta { display: flex; flex-wrap: wrap; gap: 0.4rem; align-items: center; }
    .event-pill { display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.2rem 0.6rem; border-radius: 999px; font-size: 0.72rem; font-weight: 600; }
    .pill-public  { background: rgba(46,204,113,0.2);  color: #2ecc71; }
    .pill-request { background: rgba(241,196,15,0.2);  color: #f1c40f; }
    .pill-private { background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.6); }
    .pill-full    { background: rgba(231,76,60,0.2);   color: #e74c3c; }
    .pill-actif   { background: rgba(46,204,113,0.15); color: #2ecc71; }
    .pill-annule  { background: rgba(231,76,60,0.15);  color: #e74c3c; }
    .pill-complet { background: rgba(155,89,182,0.2);  color: #9b59b6; }
    .event-date { color: rgba(255,255,255,0.7); font-size: 0.82rem; display: flex; align-items: center; gap: 0.3rem; }
    .event-date svg { width: 14px; height: 14px; fill: currentColor; }
    .event-price { color: rgba(255,255,255,0.7); font-size: 0.8rem; }
    .event-organizer { color: rgba(255,255,255,0.5); font-size: 0.75rem; }
    .event-spots { color: rgba(255,255,255,0.6); font-size: 0.75rem; }
    .empty-state { text-align: center; padding: 3rem 1rem; color: rgba(255,255,255,0.5); font-size: 0.9rem; }
    .my-badge { background: rgba(255,255,255,0.15); color: #fff; border-radius: 999px; padding: 0.15rem 0.5rem; font-size: 0.65rem; font-weight: 700; text-transform: uppercase; }
</style>
@endsection

@section('content')
<div class="events-header">
    <h1>{{ __('app.events') }}</h1>
    <a href="{{ route('events.create') }}" class="btn-create">
        <svg viewBox="0 0 24 24" style="width:16px;height:16px;fill:currentColor;"><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zm-7-9h-2v3H7v2h3v3h2v-3h3v-2h-3z"/></svg>
        {{ __('app.event_create') }}
    </a>
</div>

{{-- My events --}}
@if($myEvents->isNotEmpty())
    <div class="section-title">{{ __('app.event_my') }}</div>
    <div class="events-grid">
        @foreach($myEvents as $event)
            <a href="{{ route('events.show', $event) }}" class="card event-card">
                <div class="event-title">{{ $event->titre }}</div>
                <div class="event-meta">
                    @php
                        $accessClass = ['public' => 'pill-public', 'sur_demande' => 'pill-request', 'prive' => 'pill-private'][$event->type_acces];
                        $accessLabel = ['public' => __('app.event_public'), 'sur_demande' => __('app.event_on_request'), 'prive' => __('app.event_private')][$event->type_acces];
                    @endphp
                    <span class="event-pill {{ $accessClass }}">{{ $accessLabel }}</span>
                    @if($event->statut !== 'actif')
                        <span class="event-pill pill-{{ $event->statut }}">{{ ucfirst($event->statut) }}</span>
                    @endif
                    @if($event->creator_id === Auth::id())
                        <span class="my-badge">Organisateur</span>
                    @endif
                </div>
                <div class="event-date">
                    <svg viewBox="0 0 24 24"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>
                    {{ $event->date_evenement->translatedFormat('D j M') }} à {{ \Carbon\Carbon::parse($event->heure_debut)->format('H:i') }}
                </div>
                @if($event->lieu)
                    <div class="event-date">
                        <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                        {{ $event->lieu }}
                    </div>
                @endif
                <div class="event-spots">
                    {{ $event->confirmedParticipants->count() }} participant{{ $event->confirmedParticipants->count() !== 1 ? 's' : '' }}
                    @if($event->max_participants) / {{ $event->max_participants }} max @endif
                </div>
            </a>
        @endforeach
    </div>
@endif

{{-- Public upcoming events --}}
@if($upcomingEvents->isNotEmpty())
    <div class="section-title">{{ __('app.event_upcoming') }} — {{ __('app.event_public') }}</div>
    <div class="events-grid">
        @foreach($upcomingEvents as $event)
            <a href="{{ route('events.show', $event) }}" class="card event-card">
                <div class="event-title">{{ $event->titre }}</div>
                <div class="event-meta">
                    <span class="event-pill pill-public">{{ __('app.event_public') }}</span>
                    @if($event->isFull()) <span class="event-pill pill-full">{{ __('app.event_full') }}</span> @endif
                </div>
                <div class="event-date">
                    <svg viewBox="0 0 24 24"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>
                    {{ $event->date_evenement->translatedFormat('D j M') }} à {{ \Carbon\Carbon::parse($event->heure_debut)->format('H:i') }}
                </div>
                @if($event->lieu)
                    <div class="event-date">
                        <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                        {{ $event->lieu }}
                    </div>
                @endif
                <div class="event-organizer">{{ __('app.event_organizer') }}: {{ $event->creator->prenom }} {{ $event->creator->nom }}</div>
                @if($event->prix > 0)
                    <div class="event-price">{{ number_format($event->prix, 2) }} $</div>
                @else
                    <div class="event-price">{{ __('app.event_free') }}</div>
                @endif
            </a>
        @endforeach
    </div>
@endif

{{-- On-request events --}}
@if($requestEvents->isNotEmpty())
    <div class="section-title">{{ __('app.event_upcoming') }} — {{ __('app.event_on_request') }}</div>
    <div class="events-grid">
        @foreach($requestEvents as $event)
            <a href="{{ route('events.show', $event) }}" class="card event-card">
                <div class="event-title">{{ $event->titre }}</div>
                <div class="event-meta">
                    <span class="event-pill pill-request">{{ __('app.event_on_request') }}</span>
                    @if($event->isFull()) <span class="event-pill pill-full">{{ __('app.event_full') }}</span> @endif
                </div>
                <div class="event-date">
                    <svg viewBox="0 0 24 24"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>
                    {{ $event->date_evenement->translatedFormat('D j M') }} à {{ \Carbon\Carbon::parse($event->heure_debut)->format('H:i') }}
                </div>
                @if($event->lieu)
                    <div class="event-date">
                        <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                        {{ $event->lieu }}
                    </div>
                @endif
                <div class="event-organizer">{{ __('app.event_organizer') }}: {{ $event->creator->prenom }} {{ $event->creator->nom }}</div>
                @if($event->prix > 0)
                    <div class="event-price">{{ number_format($event->prix, 2) }} $</div>
                @else
                    <div class="event-price">{{ __('app.event_free') }}</div>
                @endif
            </a>
        @endforeach
    </div>
@endif

@if($myEvents->isEmpty() && $upcomingEvents->isEmpty() && $requestEvents->isEmpty())
    <div class="empty-state">
        <svg viewBox="0 0 24 24" style="width:48px;height:48px;fill:rgba(255,255,255,0.3);margin-bottom:1rem;display:block;margin-left:auto;margin-right:auto;"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>
        Aucun événement disponible pour le moment.<br>
        <a href="{{ route('events.create') }}" style="color:#fff;font-weight:600;">Crée le premier!</a>
    </div>
@endif
@endsection
