@extends('layouts.app')

@section('title', 'Comptes signales')

@section('styles')
<style>
    .flagged-page { display: grid; gap: 1.5rem; }
    .panel { padding: 1.4rem; }
    .panel h1 { color: #fff; margin-bottom: 0.6rem; }
    .panel p { color: rgba(255,255,255,0.7); margin: 0; }
    .panel-top { display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; }
    .back-link { display: inline-flex; align-items: center; gap: 0.4rem; text-decoration: none; color: rgba(255,255,255,.7); font-size: 0.82rem; font-weight: 600; padding: 0.55rem 0.95rem; border: 1px solid rgba(255,255,255,.2); border-radius: 999px; }
    .flagged-list { display: grid; gap: 1rem; }
    .flagged-card { padding: 1.2rem; border-radius: 18px; background: rgba(255,255,255,0.08); }
    .flagged-head { display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem; }
    .flagged-avatar { width: 56px; height: 56px; border-radius: 50%; background: rgba(255,255,255,.16); display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; }
    .flagged-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .flagged-avatar svg { width: 26px; height: 26px; fill: rgba(255,255,255,.75); }
    .flagged-name { color: #fff; font-weight: 700; font-size: 1rem; }
    .flagged-meta { color: rgba(255,255,255,.6); font-size: 0.8rem; margin-top: 0.15rem; }
    .flagged-badges { display: flex; gap: 0.45rem; flex-wrap: wrap; margin-left: auto; }
    .badge { padding: 0.24rem 0.7rem; border-radius: 999px; font-size: 0.72rem; font-weight: 700; }
    .badge-open { background: rgba(255,123,114,.2); color: #ffb4ae; border: 1px solid rgba(255,123,114,.4); }
    .badge-reviewed { background: rgba(46,204,113,.16); color: #2ecc71; border: 1px solid rgba(46,204,113,.32); }
    .flagged-actions { display: flex; gap: 0.65rem; flex-wrap: wrap; margin-bottom: 1rem; }
    .flagged-actions form { margin: 0; }
    .action-btn { border: none; border-radius: 999px; padding: 0.7rem 1rem; font-family: 'Poppins', sans-serif; font-size: 0.8rem; font-weight: 700; cursor: pointer; }
    .action-blacklist { background: rgba(231,76,60,.18); color: #ffb4ae; border: 1px solid rgba(231,76,60,.38); }
    .action-delete { background: #fff; color: #c0392b; }
    .reports-list { display: grid; gap: 0.8rem; }
    .report-item { border-radius: 14px; padding: 0.9rem 1rem; background: rgba(0,0,0,.14); border: 1px solid rgba(255,255,255,.08); }
    .report-head { display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 0.45rem; flex-wrap: wrap; }
    .report-head strong { color: #fff; }
    .report-head span { color: rgba(255,255,255,.55); font-size: 0.74rem; }
    .report-reason { color: rgba(255,255,255,.86); font-size: 0.84rem; line-height: 1.55; margin: 0; }
    .report-review { margin-top: 0.75rem; }
    .report-review button { border: none; border-radius: 999px; padding: 0.5rem 0.9rem; font-family: 'Poppins', sans-serif; font-size: 0.74rem; font-weight: 700; cursor: pointer; background: rgba(255,255,255,.12); color: #fff; }
</style>
@endsection

@section('content')
<div class="flagged-page">
    <div class="card panel">
        <div class="panel-top">
            <div>
                <h1>Comptes signales</h1>
                <p>Les admins peuvent voir pourquoi un profil a ete signale, puis blacklister ou supprimer le compte.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="back-link">Retour admin</a>
        </div>
    </div>

    <div class="card panel">
        @if ($reports->isEmpty())
            <p>Aucun signalement pour le moment.</p>
        @else
            <div class="flagged-list">
                @foreach ($reports as $reportedUserId => $userReports)
                    @php
                        $firstReport = $userReports->first();
                        $reportedUser = $firstReport?->reportedUser;
                        $openCount = $userReports->whereNull('reviewed_at')->count();
                    @endphp
                    <div class="flagged-card">
                        <div class="flagged-head">
                            <div class="flagged-avatar">
                                @if ($reportedUser?->avatar_url)
                                    <img src="{{ asset('storage/' . $reportedUser->avatar_url) }}" alt="">
                                @else
                                    <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                                @endif
                            </div>
                            <div>
                                <div class="flagged-name">{{ $reportedUser?->prenom }} {{ $reportedUser?->nom }}</div>
                                <div class="flagged-meta">
                                    {{ $reportedUser?->email }}
                                    @if ($reportedUser?->numero_programme)
                                        &bull; {{ $reportedUser->numero_programme }}
                                    @endif
                                </div>
                            </div>
                            <div class="flagged-badges">
                                <span class="badge {{ $openCount > 0 ? 'badge-open' : 'badge-reviewed' }}">
                                    {{ $openCount > 0 ? $openCount . ' ouvert(s)' : 'Tout traite' }}
                                </span>
                            </div>
                        </div>

                        @if ($reportedUser)
                            <div class="flagged-actions">
                                <form method="POST" action="{{ route('admin.users.blacklist', $reportedUser) }}">
                                    @csrf
                                    <button type="submit" class="action-btn action-blacklist">
                                        {{ $reportedUser->blacklisted ? 'Retirer blacklist' : 'Blacklister le compte' }}
                                    </button>
                                </form>
                                @if ($reportedUser->role !== 'admin')
                                    <form method="POST" action="{{ route('admin.users.delete', $reportedUser) }}" onsubmit="return confirm('Supprimer definitivement {{ $reportedUser->prenom }} {{ $reportedUser->nom }} ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-delete">Terminer le compte</button>
                                    </form>
                                @endif
                            </div>
                        @endif

                        <div class="reports-list">
                            @foreach ($userReports as $report)
                                <div class="report-item">
                                    <div class="report-head">
                                        <strong>{{ $report->reporter?->prenom }} {{ $report->reporter?->nom }}</strong>
                                        <span>
                                            {{ $report->created_at->diffForHumans() }}
                                            @if ($report->reviewed_at)
                                                &bull; traite {{ $report->reviewed_at->diffForHumans() }}
                                            @endif
                                        </span>
                                    </div>
                                    <p class="report-reason">{{ $report->reason }}</p>
                                    @if (! $report->reviewed_at)
                                        <form method="POST" action="{{ route('admin.reports.review', $report) }}" class="report-review">
                                            @csrf
                                            <button type="submit">Marquer comme traite</button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
