@extends('layouts.app')

@section('title', 'Admin')

@section('styles')
<style>
    .admin-grid { display: grid; gap: 1.5rem; }
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 1rem; }
    .stat-card, .admin-links { padding: 1.4rem; }
    .stat-card h2, .admin-links h2 { color: rgba(255, 255, 255, 0.75); font-size: 0.86rem; text-transform: uppercase; margin-bottom: 0.55rem; }
    .stat-card strong { color: #fff; font-size: 2rem; }
    .admin-links p { color: rgba(255, 255, 255, 0.76); margin-bottom: 1rem; }
    .admin-actions { display: flex; gap: 0.8rem; flex-wrap: wrap; }
    .admin-actions a { text-decoration: none; color: #fff; padding: 0.8rem 1rem; border-radius: 999px; background: rgba(255, 255, 255, 0.15); font-weight: 700; }
    @media (max-width: 800px) { .stats-grid { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<div class="admin-grid">
    <div class="stats-grid">
        <div class="card stat-card"><h2>Utilisateurs</h2><strong>{{ $stats['users'] }}</strong></div>
        <div class="card stat-card"><h2>Intérêts</h2><strong>{{ $stats['interets'] }}</strong></div>
        <div class="card stat-card"><h2>Blacklist</h2><strong>{{ $stats['blacklisted'] }}</strong></div>
        <div class="card stat-card"><h2>Signalements</h2><strong>{{ $stats['flagged'] }}</strong></div>
        <div class="card stat-card"><h2>Groupes</h2><strong>{{ $stats['groups'] }}</strong></div>
        <div class="card stat-card"><h2>Événements</h2><strong>{{ $stats['events'] }}</strong></div>
    </div>
    <div class="card admin-links">
        <h2>Administration</h2>
        <p>Le panneau admin permet les CRUD sur les comptes utilisateurs, les hobbies, les groupes et les événements.</p>
        <div class="admin-actions">
            <a href="{{ route('admin.users') }}">Gérer les utilisateurs</a>
            <a href="{{ route('admin.interets') }}">Gérer les intérêts</a>
            <a href="{{ route('admin.groups') }}">Gérer les groupes</a>
            <a href="{{ route('admin.events') }}">Gérer les événements</a>
            <a href="{{ route('admin.flagged') }}">Voir les comptes signal&eacute;s</a>
        </div>
    </div>
</div>
@endsection
