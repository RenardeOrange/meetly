@extends('layouts.app')

@section('title', 'Admin — Evenements')

@section('styles')
<style>
    .admin-events { display: grid; gap: 1.5rem; }
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
    .events-list { display: grid; gap: 1.2rem; }
    .event-card { padding: 1.2rem; border-radius: 18px; background: rgba(255,255,255,0.08); }
    .event-card-header { margin-bottom: 0.75rem; }
    .event-title { color: #fff; font-weight: 700; font-size: 1rem; margin-bottom: 0.25rem; }
    .event-meta { color: rgba(255,255,255,0.65); font-size: 0.8rem; }
    .event-badges { display: flex; flex-wrap: wrap; gap: 0.4rem; margin-bottom: 0.8rem; }
    .badge { padding: 0.25rem 0.65rem; border-radius: 999px; font-size: 0.72rem; font-weight: 600; }
    .badge-public   { background: rgba(46,204,113,0.2);  color: #2ecc71; border: 1px solid rgba(46,204,113,0.4); }
    .badge-request  { background: rgba(241,196,15,0.2);  color: #f1c40f; border: 1px solid rgba(241,196,15,0.4); }
    .badge-private  { background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.7); border: 1px solid rgba(255,255,255,0.2); }
    .badge-actif    { background: rgba(46,204,113,0.15); color: #2ecc71; border: 1px solid rgba(46,204,113,0.3); }
    .badge-annule   { background: rgba(231,76,60,0.2);   color: #e74c3c; border: 1px solid rgba(231,76,60,0.4); }
    .badge-complet  { background: rgba(155,89,182,0.2);  color: #9b59b6; border: 1px solid rgba(155,89,182,0.4); }
    .badge-info     { background: rgba(52,152,219,0.2);  color: #3498db; border: 1px solid rgba(52,152,219,0.4); }
    .update-form { display: grid; gap: 0.65rem; margin-bottom: 0.75rem; }
    .form-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 0.65rem; }
    .form-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.65rem; }
    .form-row-4 { display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 0.65rem; }
    .update-form input, .update-form textarea, .update-form select { width: 100%; padding: 0.7rem 0.9rem; border-radius: 14px; border: 1px solid rgba(255,255,255,0.18); background: rgba(255,255,255,0.12); color: #fff; font-family: 'Poppins', sans-serif; outline: none; font-size: 0.85rem; }
    .update-form textarea { resize: vertical; min-height: 72px; }
    .update-form select option { color: #222; }
    label.form-label { color: rgba(255,255,255,0.75); font-size: 0.78rem; font-weight: 600; display: block; margin-bottom: 0.25rem; }
    .btn-save { border: none; border-radius: 999px; padding: 0.7rem 1.4rem; font-weight: 700; cursor: pointer; background: #fff; color: #c0392b; font-family: 'Poppins', sans-serif; white-space: nowrap; }
    .action-row { display: flex; gap: 0.65rem; flex-wrap: wrap; align-items: center; }
    .action-btn { border: none; border-radius: 999px; padding: 0.6rem 1.1rem; font-weight: 600; cursor: pointer; font-family: 'Poppins', sans-serif; font-size: 0.82rem; }
    .action-btn-delete { background: rgba(231,76,60,0.28); color: #fff; }
    @media (max-width: 700px) { .form-row-2, .form-row-3, .form-row-4 { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<div class="admin-events">
    <div class="card panel">
        <h1>Panneau administrateur — Evenements</h1>
        <div class="stats-row">
            <span class="stat-chip">{{ $events->count() }} evenement(s)</span>
            <span class="stat-chip">{{ $events->where('statut', 'actif')->count() }} actif(s)</span>
            <span class="stat-chip">{{ $events->where('statut', 'annule')->count() }} annule(s)</span>
            <span class="stat-chip">{{ $events->where('statut', 'complet')->count() }} complet(s)</span>
        </div>
        <form method="GET" action="{{ route('admin.events') }}" class="search-bar">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Chercher par titre d'evenement">
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
        <div class="events-list">
            @forelse($events as $event)
                <div class="event-card">
                    <div class="event-card-header">
                        <div class="event-title">{{ $event->titre }}</div>
                        <div class="event-meta">
                            Organisateur: {{ $event->creator->prenom }} {{ $event->creator->nom }}
                            @if($event->group) &bull; Groupe: {{ $event->group->nom }} @endif
                            &bull; {{ $event->date_evenement->format('d/m/Y') }} à {{ \Carbon\Carbon::parse($event->heure_debut)->format('H:i') }}
                        </div>
                    </div>

                    <div class="event-badges">
                        @php
                            $accessBadge = ['public' => 'badge-public', 'sur_demande' => 'badge-request', 'prive' => 'badge-private'][$event->type_acces];
                            $accessLabel = ['public' => 'Public', 'sur_demande' => 'Sur demande', 'prive' => 'Prive'][$event->type_acces];
                        @endphp
                        <span class="badge {{ $accessBadge }}">{{ $accessLabel }}</span>
                        <span class="badge badge-{{ $event->statut }}">{{ ucfirst($event->statut) }}</span>
                        <span class="badge badge-info">{{ $event->confirmedParticipants->count() }} participant(s){{ $event->max_participants ? ' / ' . $event->max_participants . ' max' : '' }}</span>
                        @if($event->prix > 0)
                            <span class="badge badge-info">{{ number_format($event->prix, 2) }} $</span>
                        @else
                            <span class="badge badge-info">Gratuit</span>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('admin.events.update', $event) }}" class="update-form">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="form-label">Titre</label>
                            <input type="text" name="titre" value="{{ $event->titre }}" maxlength="100" required>
                        </div>

                        <div>
                            <label class="form-label">Description</label>
                            <textarea name="description" maxlength="2000">{{ $event->description }}</textarea>
                        </div>

                        <div class="form-row-2">
                            <div>
                                <label class="form-label">Date</label>
                                <input type="date" name="date_evenement" value="{{ $event->date_evenement->format('Y-m-d') }}" required>
                            </div>
                            <div>
                                <label class="form-label">Heure</label>
                                <input type="time" name="heure_debut" value="{{ \Carbon\Carbon::parse($event->heure_debut)->format('H:i') }}" required>
                            </div>
                        </div>

                        <div>
                            <label class="form-label">Lieu</label>
                            <input type="text" name="lieu" value="{{ $event->lieu }}" maxlength="200" placeholder="Lieu de l'evenement">
                        </div>

                        <div class="form-row-4">
                            <div>
                                <label class="form-label">Max participants</label>
                                <input type="number" name="max_participants" value="{{ $event->max_participants }}" min="2" placeholder="Illimite">
                            </div>
                            <div>
                                <label class="form-label">Prix ($)</label>
                                <input type="number" name="prix" value="{{ $event->prix }}" min="0" step="0.01" placeholder="0.00">
                            </div>
                            <div>
                                <label class="form-label">Acces</label>
                                <select name="type_acces" required>
                                    <option value="public"      {{ $event->type_acces === 'public'      ? 'selected' : '' }}>Public</option>
                                    <option value="sur_demande" {{ $event->type_acces === 'sur_demande' ? 'selected' : '' }}>Sur demande</option>
                                    <option value="prive"       {{ $event->type_acces === 'prive'       ? 'selected' : '' }}>Prive</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Statut</label>
                                <select name="statut" required>
                                    <option value="actif"   {{ $event->statut === 'actif'   ? 'selected' : '' }}>Actif</option>
                                    <option value="annule"  {{ $event->statut === 'annule'  ? 'selected' : '' }}>Annule</option>
                                    <option value="complet" {{ $event->statut === 'complet' ? 'selected' : '' }}>Complet</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <button type="submit" class="btn-save">Modifier</button>
                        </div>
                    </form>

                    <div class="action-row">
                        <form method="POST" action="{{ route('admin.events.delete', $event) }}"
                              onsubmit="return confirm('Supprimer definitivement l\'evenement « {{ addslashes($event->titre) }} » ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn action-btn-delete">Supprimer l'evenement</button>
                        </form>
                        <a href="{{ route('events.show', $event) }}" style="color:rgba(255,255,255,0.7);font-size:0.82rem;text-decoration:none;">Voir la page →</a>
                    </div>
                </div>
            @empty
                <div style="color:rgba(255,255,255,0.5);text-align:center;padding:2rem;">Aucun evenement trouve.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
