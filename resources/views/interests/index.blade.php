@extends('layouts.app')

@section('title', 'Mes interets')

@section('styles')
<style>
    .interest-page { display: grid; grid-template-columns: 300px minmax(0, 1fr); gap: 1.5rem; }
    .panel { padding: 1.4rem; }
    .panel h1, .panel h2 { color: #fff; font-size: 1.2rem; margin-bottom: 0.8rem; }
    .muted { color: rgba(255, 255, 255, 0.72); font-size: 0.88rem; line-height: 1.5; }
    .filter-form { display: grid; gap: 1rem; margin-top: 1rem; }
    .filter-form label { color: rgba(255, 255, 255, 0.85); font-size: 0.82rem; font-weight: 600; display: block; margin-bottom: 0.35rem; }
    .filter-form input, .filter-form select { width: 100%; padding: 0.85rem 1rem; border-radius: 16px; border: 1px solid rgba(255, 255, 255, 0.22); background: rgba(255, 255, 255, 0.12); color: #fff; outline: none; }
    .filter-form select option { color: #222; }
    .actions { display: flex; gap: 0.75rem; }
    .actions button, .actions a, .match-link, .toggle-form button { border: none; border-radius: 999px; padding: 0.8rem 1rem; font-weight: 700; text-align: center; text-decoration: none; cursor: pointer; }
    .actions button, .toggle-form button.active { background: #fff; color: #c0392b; }
    .actions a, .match-link, .toggle-form button { background: rgba(255, 255, 255, 0.14); color: #fff; }
    .interest-groups { display: grid; gap: 1rem; }
    .interest-group { padding: 1.1rem; border-radius: 18px; background: rgba(255, 255, 255, 0.08); }
    .interest-group h3 { color: #fff; margin-bottom: 0.85rem; }
    .interest-list { display: grid; gap: 0.7rem; }
    .interest-item { display: grid; grid-template-columns: 1fr auto auto; gap: 0.75rem; align-items: center; padding: 0.85rem 0.95rem; border-radius: 16px; background: rgba(255, 255, 255, 0.08); }
    .interest-item strong { display: block; color: #fff; margin-bottom: 0.2rem; }
    .interest-item span { color: rgba(255, 255, 255, 0.68); font-size: 0.8rem; }
    .empty { color: rgba(255, 255, 255, 0.72); text-align: center; padding: 2rem 1rem; }
    @media (max-width: 980px) { .interest-page { grid-template-columns: 1fr; } }
    @media (max-width: 720px) { .interest-item { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<div class="interest-page">
    <aside class="card panel">
        <h1>Mes interets</h1>
        <p class="muted">Recherche par nom ou par groupe, puis ajoute les interets a ton profil.</p>
        <form method="GET" action="{{ route('interets.index') }}" class="filter-form">
            <div><label for="search">Recherche</label><input id="search" type="text" name="search" value="{{ $search }}" placeholder="Ex. musique, velo, gaming"></div>
            <div>
                <label for="categorie">Groupe d'interets</label>
                <select id="categorie" name="categorie">
                    <option value="">Tous les groupes</option>
                    @foreach ($categories as $categorie)
                        <option value="{{ $categorie }}" {{ $selectedCategorie === $categorie ? 'selected' : '' }}>{{ $categorie }}</option>
                    @endforeach
                </select>
            </div>
            <div class="actions"><button type="submit">Rechercher</button><a href="{{ route('interets.index') }}">Effacer</a></div>
        </form>
    </aside>

    <section class="card panel">
        <h2>Catalogue d'interets</h2>
        @if ($interets->isEmpty())
            <div class="empty">Aucun interet trouve avec ces filtres.</div>
        @else
            <div class="interest-groups">
                @foreach ($interets as $categorie => $liste)
                    <div class="interest-group">
                        <h3>{{ $categorie }}</h3>
                        <div class="interest-list">
                            @foreach ($liste as $interet)
                                @php $selected = in_array($interet->id, $userInteretIds, true); @endphp
                                <div class="interest-item">
                                    <div><strong>{{ $interet->nom }}</strong><span>{{ $selected ? 'Deja dans mes interets' : 'Disponible pour mon profil' }}</span></div>
                                    <form method="POST" action="{{ route('interets.toggle') }}" class="toggle-form">
                                        @csrf
                                        <input type="hidden" name="interet_id" value="{{ $interet->id }}">
                                        <button type="submit" class="{{ $selected ? 'active' : '' }}">{{ $selected ? 'Retirer de mes interets' : 'Ajouter a mes interets' }}</button>
                                    </form>
                                    <a class="match-link" href="{{ route('home', ['interets' => [$interet->id]]) }}">Chercher des matchs</a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
</div>
@endsection
