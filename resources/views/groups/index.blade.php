@extends('layouts.app')

@section('title', 'Groupes')

@section('styles')
<style>
    .groups-page { max-width: 860px; margin: 0 auto; display: flex; flex-direction: column; gap: 2rem; }
    .groups-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; }
    .groups-title { color: #fff; font-size: 1.3rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; }
    .btn-create { display: inline-flex; align-items: center; gap: 0.5rem; background: #fff; color: #c0392b; border: none; border-radius: 999px; padding: 0.7rem 1.4rem; font-family: 'Poppins', sans-serif; font-size: 0.88rem; font-weight: 700; text-decoration: none; cursor: pointer; }
    .btn-create svg { width: 18px; height: 18px; fill: currentColor; }
    .section-label { color: rgba(255,255,255,0.65); font-size: 0.78rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 0.75rem; }
    .groups-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1rem; }
    .group-card { background: rgba(255,255,255,0.12); backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.2); border-radius: 18px; padding: 1.25rem; display: flex; flex-direction: column; gap: 0.6rem; text-decoration: none; transition: all 0.25s ease; }
    .group-card:hover { background: rgba(255,255,255,0.2); transform: translateY(-2px); box-shadow: 0 8px 30px rgba(0,0,0,0.15); }
    .group-card-avatar { width: 52px; height: 52px; border-radius: 14px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; }
    .group-card-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .group-card-avatar svg { width: 28px; height: 28px; fill: rgba(255,255,255,0.7); }
    .group-card-name { color: #fff; font-weight: 700; font-size: 0.95rem; }
    .group-card-desc { color: rgba(255,255,255,0.6); font-size: 0.78rem; line-height: 1.4; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; }
    .group-card-meta { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; margin-top: 0.2rem; }
    .group-meta-pill { font-size: 0.68rem; padding: 0.22rem 0.55rem; border-radius: 999px; font-weight: 600; }
    .pill-public { background: rgba(46,204,113,0.2); color: #2ecc71; border: 1px solid rgba(46,204,113,0.3); }
    .pill-private { background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.6); }
    .pill-members { background: rgba(100,210,255,0.15); color: rgba(180,235,255,0.9); border: 1px solid rgba(100,210,255,0.25); }
    .pill-interest { background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.75); }
    .group-last-msg { color: rgba(255,255,255,0.45); font-size: 0.74rem; font-style: italic; margin-top: 0.1rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .empty-section { color: rgba(255,255,255,0.45); font-size: 0.85rem; padding: 1rem 0; }
    .btn-join { display: inline-block; background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); color: #fff; border-radius: 999px; padding: 0.35rem 0.9rem; font-size: 0.75rem; font-weight: 600; font-family: 'Poppins', sans-serif; cursor: pointer; margin-top: 0.35rem; transition: background 0.2s; }
    .btn-join:hover { background: rgba(255,255,255,0.25); }
</style>
@endsection

@section('content')
<div class="groups-page">
    <div class="groups-header">
        <div class="groups-title">Groupes</div>
        <a href="{{ route('groups.create') }}" class="btn-create">
            <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
            Créer un groupe
        </a>
    </div>

    {{-- My groups --}}
    <div>
        <div class="section-label">Mes groupes</div>
        @if($myGroups->count())
            <div class="groups-grid">
                @foreach($myGroups as $group)
                    @php $lastMsg = $group->messages->first(); @endphp
                    <a href="{{ route('groups.show', $group) }}" class="group-card">
                        <div style="display:flex;align-items:center;gap:0.75rem;">
                            <div class="group-card-avatar">
                                @if($group->avatar_url)
                                    <img src="{{ asset('storage/' . $group->avatar_url) }}" alt="">
                                @else
                                    <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                                @endif
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div class="group-card-name">{{ $group->nom }}</div>
                                <div class="group-card-meta">
                                    <span class="group-meta-pill {{ $group->est_public ? 'pill-public' : 'pill-private' }}">{{ $group->est_public ? 'Public' : 'Privé' }}</span>
                                    <span class="group-meta-pill pill-members">{{ $group->members->count() }} membre{{ $group->members->count() > 1 ? 's' : '' }}</span>
                                </div>
                            </div>
                        </div>
                        @if($group->description)
                            <div class="group-card-desc">{{ $group->description }}</div>
                        @endif
                        @foreach($group->interets->take(3) as $interet)
                            <span class="group-meta-pill pill-interest">{{ $interet->nom }}</span>
                        @endforeach
                        @if($lastMsg)
                            <div class="group-last-msg">{{ $lastMsg->user->prenom }}: {{ $lastMsg->contenu }}</div>
                        @endif
                    </a>
                @endforeach
            </div>
        @else
            <div class="empty-section">Tu n'es dans aucun groupe pour l'instant.</div>
        @endif
    </div>

    {{-- Public groups to discover --}}
    @if($publicGroups->count())
    <div>
        <div class="section-label">Groupes publics à rejoindre</div>
        <div class="groups-grid">
            @foreach($publicGroups as $group)
                <div class="group-card" style="cursor:default;">
                    <div style="display:flex;align-items:center;gap:0.75rem;">
                        <div class="group-card-avatar">
                            @if($group->avatar_url)
                                <img src="{{ asset('storage/' . $group->avatar_url) }}" alt="">
                            @else
                                <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                            @endif
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div class="group-card-name">{{ $group->nom }}</div>
                            <div class="group-card-meta">
                                <span class="group-meta-pill pill-public">Public</span>
                                <span class="group-meta-pill pill-members">{{ $group->members->count() }} membre{{ $group->members->count() > 1 ? 's' : '' }}</span>
                            </div>
                        </div>
                    </div>
                    @if($group->description)
                        <div class="group-card-desc">{{ $group->description }}</div>
                    @endif
                    @foreach($group->interets->take(3) as $interet)
                        <span class="group-meta-pill pill-interest">{{ $interet->nom }}</span>
                    @endforeach
                    <form method="POST" action="{{ route('groups.join', $group) }}">
                        @csrf
                        <button type="submit" class="btn-join">Rejoindre</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
