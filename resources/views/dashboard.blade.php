@extends('layouts.app')

@section('title', __('app.dashboard_title'))

@section('styles')
<style>
    .db-page { display: grid; gap: 1.5rem; }

    /* ── Section titles ── */
    .section-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.1rem; }
    .section-head h2 { color: #fff; font-size: 1.05rem; font-weight: 700; margin: 0; }
    .back-link { display: inline-flex; align-items: center; gap: 0.4rem; text-decoration: none; color: rgba(255,255,255,.7); font-size: 0.82rem; font-weight: 600; padding: 0.4rem 0.9rem; border: 1px solid rgba(255,255,255,.2); border-radius: 999px; transition: all .2s; }
    .back-link:hover { background: rgba(255,255,255,.1); color: #fff; }

    /* ── Stat cards ── */
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem; }
    .stat-card { padding: 1.2rem 1rem; border-radius: 20px; text-align: center; }
    .stat-icon { font-size: 1.6rem; margin-bottom: 0.4rem; }
    .stat-value { color: #fff; font-size: 2rem; font-weight: 800; line-height: 1; }
    .stat-label { color: rgba(255,255,255,.65); font-size: 0.75rem; font-weight: 600; margin-top: 0.3rem; text-transform: uppercase; letter-spacing: .04em; }
    .stat-card.green  { background: rgba(46,204,113,.14);  border: 1px solid rgba(46,204,113,.3); }
    .stat-card.red    { background: rgba(231,76,60,.14);   border: 1px solid rgba(231,76,60,.3); }
    .stat-card.blue   { background: rgba(100,180,255,.12); border: 1px solid rgba(100,180,255,.25); }
    .stat-card.gold   { background: rgba(253,216,53,.12);  border: 1px solid rgba(253,216,53,.25); }
    .stat-card.purple { background: rgba(180,100,255,.12); border: 1px solid rgba(180,100,255,.25); }
    .stat-card.teal   { background: rgba(0,200,190,.12);   border: 1px solid rgba(0,200,190,.25); }

    /* ── Tab bar ── */
    .tab-bar { display: flex; gap: 0.4rem; flex-wrap: wrap; margin-bottom: 1.2rem; }
    .tab-btn { padding: 0.55rem 1.1rem; border-radius: 999px; border: 1px solid rgba(255,255,255,.2); background: rgba(255,255,255,.08); color: rgba(255,255,255,.75); font-family: 'Poppins', sans-serif; font-size: 0.8rem; font-weight: 600; cursor: pointer; transition: all .2s; }
    .tab-btn.active, .tab-btn:hover { background: #fff; color: #c0392b; border-color: #fff; }
    .tab-pane { display: none; }
    .tab-pane.active { display: block; }

    /* ── History cards ── */
    .history-list { display: grid; gap: 0.65rem; }
    .history-item { display: flex; align-items: center; gap: 1rem; padding: 0.85rem 1rem; border-radius: 16px; background: rgba(255,255,255,.07); }
    .history-avatar { width: 46px; height: 46px; border-radius: 50%; background: rgba(255,255,255,.2); display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; }
    .history-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .history-avatar svg { width: 22px; height: 22px; fill: rgba(255,255,255,.75); }
    .history-info { flex: 1; min-width: 0; }
    .history-name { color: #fff; font-weight: 600; font-size: 0.9rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .history-meta { color: rgba(255,255,255,.55); font-size: 0.74rem; margin-top: 0.15rem; }
    .history-badge { padding: 0.22rem 0.6rem; border-radius: 999px; font-size: 0.7rem; font-weight: 700; white-space: nowrap; flex-shrink: 0; }
    .badge-like    { background: rgba(46,204,113,.2);  color: #2ecc71; border: 1px solid rgba(46,204,113,.35); }
    .badge-pass    { background: rgba(231,76,60,.15);  color: #e74c3c; border: 1px solid rgba(231,76,60,.3); }
    .badge-match   { background: rgba(253,216,53,.18); color: #fdd835; border: 1px solid rgba(253,216,53,.35); }
    .badge-pending { background: rgba(100,180,255,.16); color: #64b5f6; border: 1px solid rgba(100,180,255,.3); }

    /* ── Undo button ── */
    .undo-form { margin-left: auto; flex-shrink: 0; }
    .btn-undo { border: none; border-radius: 999px; padding: 0.38rem 0.85rem; font-family: 'Poppins', sans-serif; font-size: 0.72rem; font-weight: 700; cursor: pointer; background: rgba(255,255,255,.12); color: rgba(255,255,255,.8); transition: background .15s; }
    .btn-undo:hover { background: rgba(255,255,255,.22); color: #fff; }

    /* ── Match cards (mutual) ── */
    .match-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; }
    .match-card { padding: 1.1rem; border-radius: 18px; background: rgba(46,204,113,.1); border: 1px solid rgba(46,204,113,.25); display: flex; align-items: center; gap: 0.85rem; }
    .match-avatar { width: 52px; height: 52px; border-radius: 50%; background: rgba(255,255,255,.2); display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; }
    .match-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .match-avatar svg { width: 24px; height: 24px; fill: rgba(255,255,255,.75); }
    .match-name { color: #fff; font-weight: 700; font-size: 0.9rem; }
    .match-date { color: rgba(255,255,255,.55); font-size: 0.74rem; margin-top: 0.15rem; }
    .match-link-btn { display: inline-block; margin-top: 0.5rem; font-size: 0.72rem; font-weight: 700; color: #2ecc71; text-decoration: none; }
    .match-link-btn:hover { text-decoration: underline; }
    .blocked-list { display: grid; gap: 0.75rem; }
    .blocked-item { display: flex; align-items: center; gap: 1rem; padding: 0.95rem 1rem; border-radius: 16px; background: rgba(231,76,60,.12); border: 1px solid rgba(231,76,60,.25); }
    .blocked-meta { color: rgba(255,255,255,.58); font-size: 0.74rem; margin-top: 0.15rem; }
    .blocked-actions { margin-left: auto; }
    .btn-unblock { border: none; border-radius: 999px; padding: 0.45rem 0.9rem; font-family: 'Poppins', sans-serif; font-size: 0.74rem; font-weight: 700; cursor: pointer; background: #fff; color: #c0392b; }

    /* ── Empty state ── */
    .empty-state { text-align: center; padding: 2.5rem 1rem; color: rgba(255,255,255,.55); font-size: 0.88rem; }

    /* ── Flash ── */
    .flash { padding: .7rem 1rem; border-radius: 12px; margin-bottom: 1rem; font-size: .86rem; }
    .flash-success { background: rgba(46,204,113,.16); color: #fff; border: 1px solid rgba(46,204,113,.35); }

    @media (max-width: 640px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .match-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="db-page">

    {{-- ── Header ── --}}
    <div class="card" style="padding:1.2rem 1.4rem;">
        <div class="section-head" style="margin:0;">
            <div>
                <h2 style="font-size:1.2rem;margin-bottom:.2rem;">&#128202; {{ __('app.dashboard_title') }}</h2>
                <p style="color:rgba(255,255,255,.6);font-size:.82rem;margin:0;">{{ __('app.dashboard_subtitle') }}</p>
            </div>
            <div style="display:flex;gap:.6rem;flex-wrap:wrap;">
                <a href="{{ route('home') }}" class="back-link">{{ __('app.nav_discover') }}</a>
                <a href="{{ route('profile.edit') }}" class="back-link">&#8592; {{ __('app.back_to_profile') }}</a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="flash flash-success">{{ session('success') }}</div>
    @endif

    {{-- ── Stats ── --}}
    <div class="card" style="padding:1.4rem;">
        <div class="section-head"><h2>{{ __('app.stats') }}</h2></div>
        <div class="stats-grid">
            <div class="stat-card green">
                <div class="stat-icon">&#128077;</div>
                <div class="stat-value">{{ $totalLikes }}</div>
                <div class="stat-label">{{ __('app.likes_sent') }}</div>
            </div>
            <div class="stat-card red">
                <div class="stat-icon">&#10060;</div>
                <div class="stat-value">{{ $totalPasses }}</div>
                <div class="stat-label">{{ __('app.tab_passes') }}</div>
            </div>
            <div class="stat-card blue">
                <div class="stat-icon">&#128140;</div>
                <div class="stat-value">{{ $totalIncoming }}</div>
                <div class="stat-label">{{ __('app.likes_received') }}</div>
            </div>
            <div class="stat-card gold">
                <div class="stat-icon">&#127881;</div>
                <div class="stat-value">{{ $totalMatches }}</div>
                <div class="stat-label">{{ __('app.mutual_matches') }}</div>
            </div>
            <div class="stat-card purple">
                <div class="stat-icon">&#127919;</div>
                <div class="stat-value">{{ $acceptanceRate }}%</div>
                <div class="stat-label">{{ __('app.acceptance_rate') }}</div>
            </div>
            <div class="stat-card teal">
                <div class="stat-icon">&#128257;</div>
                <div class="stat-value">{{ $likeRatio }}%</div>
                <div class="stat-label">{{ __('app.like_swipe_ratio') }}</div>
            </div>
        </div>
    </div>

    {{-- ── Tabbed history ── --}}
    <div class="card" style="padding:1.4rem;">
        <div class="section-head"><h2>{{ __('app.history') }}</h2></div>

        <div class="tab-bar">
            <button class="tab-btn active" data-tab="all">{{ __('app.tab_all') }} ({{ $history->count() }})</button>
            <button class="tab-btn" data-tab="likes">Likes ({{ $myLikes->count() }})</button>
            <button class="tab-btn" data-tab="passes">{{ __('app.tab_passes') }} ({{ $myPasses->count() }})</button>
            <button class="tab-btn" data-tab="incoming">{{ __('app.tab_received') }} ({{ $incomingLikes->count() }})</button>
            <button class="tab-btn" data-tab="matches">{{ __('app.tab_matches') }} ({{ $mutualMatches->count() }})</button>
            <button class="tab-btn" data-tab="blocked" id="tab-button-blocked">{{ __('app.tab_blocked') }} ({{ $blockedUsers->count() }})</button>
        </div>

        {{-- All swipes tab --}}
        <div class="tab-pane active" id="tab-all">
            @if ($history->isEmpty())
                <div class="empty-state">{{ __('app.no_swipes') }}</div>
            @else
                <div class="history-list">
                    @foreach ($history as $match)
                        @php
                            $profile = $match->user2;
                            $isMatch  = $match->statut === 'accepte';
                            $isPending = $match->statut === 'en_attente';
                            $isPass   = $match->statut === 'refuse';
                        @endphp
                        <div class="history-item">
                            <div class="history-avatar">
                                @if ($profile->avatar_url)
                                    <img src="{{ asset('storage/' . $profile->avatar_url) }}" alt="">
                                @else
                                    <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                                @endif
                            </div>
                            <div class="history-info">
                                <div class="history-name">{{ $profile->prenom }} {{ $profile->nom }}</div>
                                <div class="history-meta">{{ $match->created_at->diffForHumans() }}</div>
                            </div>
                            @if ($isMatch)
                                <span class="history-badge badge-match">&#127881; {{ __('app.match_excl') }}</span>
                            @elseif ($isPending)
                                <span class="history-badge badge-pending">&#9203; {{ __('app.pending') }}</span>
                            @else
                                <span class="history-badge badge-pass">{{ __('app.passed') }}</span>
                            @endif
                            @if ($isPass || $isPending)
                                <form method="POST" action="{{ route('dashboard.undo', $match) }}" class="undo-form" onsubmit="return confirm('{{ __('app.cancel_swipe_confirm') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-undo">{{ __('app.cancel') }}</button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- My likes tab --}}
        <div class="tab-pane" id="tab-likes">
            @if ($myLikes->isEmpty())
                <div class="empty-state">{{ __('app.no_likes_sent') }}</div>
            @else
                <div class="history-list">
                    @foreach ($myLikes as $match)
                        @php $profile = $match->user2; @endphp
                        <div class="history-item">
                            <div class="history-avatar">
                                @if ($profile->avatar_url)
                                    <img src="{{ asset('storage/' . $profile->avatar_url) }}" alt="">
                                @else
                                    <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                                @endif
                            </div>
                            <div class="history-info">
                                <div class="history-name">{{ $profile->prenom }} {{ $profile->nom }}</div>
                                <div class="history-meta">{{ $match->created_at->diffForHumans() }}</div>
                            </div>
                            @if ($match->statut === 'accepte')
                                <span class="history-badge badge-match">&#127881; {{ __('app.match_excl') }}</span>
                            @else
                                <span class="history-badge badge-pending">&#9203; {{ __('app.pending') }}</span>
                                <form method="POST" action="{{ route('dashboard.undo', $match) }}" class="undo-form" onsubmit="return confirm('{{ __('app.cancel_req_confirm') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-undo">{{ __('app.cancel') }}</button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Passes tab --}}
        <div class="tab-pane" id="tab-passes">
            @if ($myPasses->isEmpty())
                <div class="empty-state">{{ __('app.no_passes') }}</div>
            @else
                <div class="history-list">
                    @foreach ($myPasses as $match)
                        @php $profile = $match->user2; @endphp
                        <div class="history-item">
                            <div class="history-avatar">
                                @if ($profile->avatar_url)
                                    <img src="{{ asset('storage/' . $profile->avatar_url) }}" alt="">
                                @else
                                    <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                                @endif
                            </div>
                            <div class="history-info">
                                <div class="history-name">{{ $profile->prenom }} {{ $profile->nom }}</div>
                                <div class="history-meta">{{ __('app.passed') }} {{ $match->created_at->diffForHumans() }}</div>
                            </div>
                            <span class="history-badge badge-pass">{{ __('app.passed') }}</span>
                            <form method="POST" action="{{ route('dashboard.undo', $match) }}" class="undo-form" onsubmit="return confirm('{{ __('app.rediscover_confirm') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-undo">&#8634; {{ __('app.rediscover') }}</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Incoming likes tab --}}
        <div class="tab-pane" id="tab-incoming">
            @if ($incomingLikes->isEmpty())
                <div class="empty-state">{{ __('app.no_likes_received') }}</div>
            @else
                <div class="history-list">
                    @foreach ($incomingLikes as $match)
                        @php $profile = $match->user1; @endphp
                        <div class="history-item">
                            <div class="history-avatar">
                                @if ($profile->avatar_url)
                                    <img src="{{ asset('storage/' . $profile->avatar_url) }}" alt="">
                                @else
                                    <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                                @endif
                            </div>
                            <div class="history-info">
                                <div class="history-name">{{ $profile->prenom }} {{ $profile->nom }}</div>
                                <div class="history-meta">
                                    {{ $match->statut === 'accepte' ? __('app.match_excl') . ' ' : __('app.liked_you') . ' · ' }}{{ $match->created_at->diffForHumans() }}
                                </div>
                            </div>
                            @if ($match->statut === 'accepte')
                                <span class="history-badge badge-match">&#127881; {{ __('app.match_excl') }}</span>
                            @else
                                <span class="history-badge badge-like">&#128077; Liké</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Mutual matches tab --}}
        <div class="tab-pane" id="tab-matches">
            @if ($mutualMatches->isEmpty())
                <div class="empty-state">{{ __('app.no_mutual_matches') }}</div>
            @else
                <div class="match-grid">
                    @foreach ($mutualMatches as $match)
                        @php
                            $other = $match->user_1_id === auth()->id() ? $match->user2 : $match->user1;
                        @endphp
                        <div class="match-card">
                            <div class="match-avatar">
                                @if ($other->avatar_url)
                                    <img src="{{ asset('storage/' . $other->avatar_url) }}" alt="">
                                @else
                                    <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                                @endif
                            </div>
                            <div>
                                <div class="match-name">{{ $other->prenom }} {{ $other->nom }}</div>
                                <div class="match-date">{{ __('app.match_excl') }} {{ $match->updated_at->diffForHumans() }}</div>
                                @if ($match->chat)
                                    <a href="{{ route('chats.show', $match->chat) }}" class="match-link-btn">{{ __('app.view_conversation') }} &#8594;</a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="tab-pane" id="tab-blocked">
            @if ($blockedUsers->isEmpty())
                <div class="empty-state">{{ __('app.no_blocked') }}</div>
            @else
                <div class="blocked-list">
                    @foreach ($blockedUsers as $block)
                        @php $profile = $block->blocked; @endphp
                        <div class="blocked-item">
                            <div class="history-avatar">
                                @if ($profile?->avatar_url)
                                    <img src="{{ asset('storage/' . $profile->avatar_url) }}" alt="">
                                @else
                                    <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                                @endif
                            </div>
                            <div class="history-info">
                                <div class="history-name">{{ $profile?->prenom }} {{ $profile?->nom }}</div>
                                <div class="blocked-meta">{{ __('app.blocked_ago') }} {{ $block->created_at->diffForHumans() }}</div>
                            </div>
                            <div class="blocked-actions">
                                @if ($profile)
                                    <form method="POST" action="{{ route('blocks.destroy', $profile) }}" onsubmit="return confirm('{{ __('app.unblock_confirm') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-unblock">{{ __('app.unblock') }}</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');

    function activateTab(name) {
        tabButtons.forEach(button => button.classList.toggle('active', button.dataset.tab === name));
        tabPanes.forEach(pane => pane.classList.toggle('active', pane.id === 'tab-' + name));
    }

    tabButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            activateTab(this.dataset.tab);
            history.replaceState(null, '', `#tab-${this.dataset.tab}`);
        });
    });

    const hashTab = window.location.hash.replace('#tab-', '');
    if (hashTab && document.getElementById('tab-' + hashTab)) {
        activateTab(hashTab);
    }
</script>
@endsection
