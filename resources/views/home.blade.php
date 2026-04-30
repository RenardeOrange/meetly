@extends('layouts.app')

@section('title', __('app.nav_discover'))

@section('styles')
<style>
    .discover-layout { display: grid; grid-template-columns: 290px minmax(0, 1fr); gap: 1.5rem; align-items: start; }
    .filters-card { padding: 1.2rem; }
    .filter-section-title { color: #fff; font-size: 1rem; font-weight: 700; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; }
    .filter-section-title svg { width: 18px; height: 18px; fill: currentColor; flex-shrink: 0; }
    .filter-form { display: grid; gap: 1rem; }
    .filter-form > label { display: block; color: rgba(255,255,255,0.85); font-size: 0.8rem; font-weight: 600; margin-bottom: 0.3rem; }
    .filter-form input[type="text"] { width: 100%; padding: 0.75rem 0.9rem; border-radius: 14px; border: 1px solid rgba(255,255,255,0.22); background: rgba(255,255,255,0.12); color: #fff; outline: none; font-family: 'Poppins', sans-serif; font-size: 0.85rem; }
    .filter-form input::placeholder { color: rgba(255,255,255,0.5); }
    .filter-group { background: rgba(255,255,255,0.06); border-radius: 14px; overflow: hidden; }
    .filter-group-toggle { width: 100%; display: flex; align-items: center; justify-content: space-between; padding: 0.7rem 0.9rem; background: none; border: none; color: #fff; font-family: 'Poppins', sans-serif; font-size: 0.82rem; font-weight: 600; cursor: pointer; }
    .filter-group-toggle .chevron { width: 16px; height: 16px; fill: currentColor; transition: transform 0.25s; flex-shrink: 0; }
    .filter-group-toggle.open .chevron { transform: rotate(180deg); }
    .filter-group-body { max-height: 0; overflow: hidden; transition: max-height 0.3s ease; }
    .filter-group-body.open { max-height: 700px; padding: 0 0.9rem 0.9rem; }
    .interest-filter-grid { display: grid; gap: 0.6rem; }
    .interest-sub-group h4 { color: rgba(255,255,255,0.65); font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.35rem; }
    .interest-options { display: flex; flex-wrap: wrap; gap: 0.35rem; }
    .interest-option { position: relative; }
    .interest-option input { position: absolute; opacity: 0; }
    .interest-option span { display: inline-flex; align-items: center; padding: 0.3rem 0.65rem; border-radius: 999px; background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.8); font-size: 0.72rem; cursor: pointer; }
    .interest-option input:checked + span { background: #fff; color: #c0392b; font-weight: 700; }
    .filter-actions { display: flex; gap: 0.6rem; }
    .btn-filter, .btn-reset { flex: 1; border: none; border-radius: 999px; padding: 0.8rem 1rem; font-weight: 700; text-decoration: none; text-align: center; cursor: pointer; font-family: 'Poppins', sans-serif; font-size: 0.85rem; }
    .btn-filter { background: #fff; color: #c0392b; }
    .btn-reset { background: rgba(255,255,255,0.14); color: #fff; }

    .swipe-section { display: flex; flex-direction: column; align-items: center; gap: 1.2rem; padding: 1.4rem; }
    .swipe-title { color: #fff; font-size: 1.2rem; font-weight: 700; text-align: center; }
    .swipe-top-actions { display: flex; gap: 0.7rem; flex-wrap: wrap; justify-content: center; margin-top: -0.4rem; }
    .btn-utility { display: inline-flex; align-items: center; justify-content: center; padding: 0.65rem 1rem; border-radius: 999px; text-decoration: none; border: 1px solid rgba(255,255,255,0.2); background: rgba(255,255,255,0.08); color: rgba(255,255,255,0.82); font-size: 0.78rem; font-weight: 600; font-family: 'Poppins', sans-serif; cursor: pointer; }
    .btn-utility:hover { background: rgba(255,255,255,0.15); color: #fff; }
    .swipe-container { position: relative; width: 340px; height: 460px; }
    .swipe-card { position: absolute; width: 100%; height: 100%; background: rgba(255,255,255,0.15); backdrop-filter: blur(20px); border: 2px solid rgba(255,255,255,0.25); border-radius: 24px; padding: 1.75rem 1.5rem; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; cursor: grab; user-select: none; transition: transform 0.4s ease, opacity 0.4s ease; will-change: transform; visibility: hidden; pointer-events: none; }
    .swipe-card.top-card { visibility: visible; pointer-events: auto; }
    .swipe-card.swiping { transition: none; cursor: grabbing; }
    .swipe-card.swipe-right { transform: translateX(150%) rotate(20deg); opacity: 0; visibility: visible; }
    .swipe-card.swipe-left { transform: translateX(-150%) rotate(-20deg); opacity: 0; visibility: visible; }
    .profile-avatar { width: 95px; height: 95px; border-radius: 50%; background: rgba(255,255,255,0.25); display: flex; align-items: center; justify-content: center; margin-bottom: 1.1rem; overflow: hidden; }
    .profile-avatar svg { width: 46px; height: 46px; fill: rgba(255,255,255,0.8); }
    .profile-name { color: #fff; font-size: 1.3rem; font-weight: 700; margin-bottom: 0.2rem; }
    .profile-sub { color: rgba(255,255,255,0.7); font-size: 0.8rem; margin-bottom: 0.2rem; }
    .profile-bio { color: rgba(255,255,255,0.85); font-size: 0.82rem; line-height: 1.5; max-height: 70px; overflow: hidden; margin-top: 0.35rem; }
    .swipe-indicator { position: absolute; top: 1.5rem; font-size: 1.1rem; font-weight: 700; padding: 0.3rem 1rem; border-radius: 8px; opacity: 0; transition: opacity 0.2s; }
    .like-indicator { right: 1.2rem; color: #2ecc71; border: 2px solid #2ecc71; }
    .nope-indicator { left: 1.2rem; color: #e74c3c; border: 2px solid #e74c3c; }
    .profile-tags { display: flex; flex-wrap: wrap; gap: 0.35rem; margin-top: 0.9rem; justify-content: center; }
    .profile-interest { padding: 0.28rem 0.58rem; border-radius: 999px; background: rgba(255,255,255,0.16); color: #fff; font-size: 0.7rem; }
    .profile-connexion { padding: 0.25rem 0.52rem; border-radius: 999px; background: rgba(100,210,255,0.18); color: rgba(180,235,255,0.95); font-size: 0.68rem; border: 1px solid rgba(100,210,255,0.3); }
    .profile-actions { display: flex; gap: 0.5rem; margin-top: 1rem; }
    .profile-action-btn { border: 1px solid rgba(255,255,255,0.18); border-radius: 999px; padding: 0.5rem 0.8rem; background: rgba(255,255,255,0.08); color: rgba(255,255,255,0.85); font-family: 'Poppins', sans-serif; font-size: 0.72rem; font-weight: 600; cursor: pointer; }
    .profile-action-btn:hover { background: rgba(255,255,255,0.14); color: #fff; }
    .profile-action-btn.danger { border-color: rgba(255,123,114,0.32); color: #ffd3d0; background: rgba(231,76,60,0.14); }
    .swipe-buttons { display: flex; gap: 1.5rem; margin-top: 0.4rem; }
    .swipe-btn { width: 60px; height: 60px; border-radius: 50%; border: 2px solid rgba(255,255,255,0.5); background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); display: flex; align-items: center; justify-content: center; cursor: pointer; }
    .swipe-btn svg { width: 28px; height: 28px; }
    .btn-nope svg { fill: #e74c3c; }
    .btn-like svg { fill: #2ecc71; }
    .empty-state { text-align: center; color: rgba(255,255,255,0.8); padding: 3rem 1rem; }
    .empty-state svg { width: 80px; height: 80px; fill: rgba(255,255,255,0.3); margin-bottom: 1rem; }
    .empty-state h2 { color: #fff; margin-bottom: 0.5rem; }
    .card-match-score { display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.28rem 0.7rem; border-radius: 999px; font-size: 0.75rem; font-weight: 700; margin-top: 0.6rem; }
    .card-match-score.high { background: rgba(46,204,113,0.22); color: #2ecc71; border: 1px solid rgba(46,204,113,0.45); }
    .card-match-score.mid { background: rgba(253,216,53,0.18); color: #fdd835; border: 1px solid rgba(253,216,53,0.4); }
    .card-match-score.low { background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.6); border: 1px solid rgba(255,255,255,0.18); }

    .msg-modal-overlay { position: fixed; inset: 0; z-index: 9999; background: rgba(0,0,0,0.65); display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px); animation: fadeIn 0.2s ease; }
    .msg-modal-overlay.hidden { display: none; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    .msg-modal { background: rgba(30,10,30,0.95); border: 1px solid rgba(255,255,255,0.18); border-radius: 24px; padding: 2rem 1.75rem; width: min(380px, 92vw); box-shadow: 0 20px 60px rgba(0,0,0,0.5); animation: slideUp 0.25s ease; }
    @keyframes slideUp { from { transform: translateY(30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    .msg-modal-title { color: #fff; font-size: 1.1rem; font-weight: 700; margin-bottom: 0.3rem; }
    .msg-modal-sub { color: rgba(255,255,255,0.6); font-size: 0.82rem; margin-bottom: 1.2rem; }
    .msg-modal textarea { width: 100%; box-sizing: border-box; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.22); border-radius: 14px; color: #fff; font-family: 'Poppins', sans-serif; font-size: 0.88rem; padding: 0.85rem 1rem; resize: none; outline: none; }
    .msg-modal textarea::placeholder { color: rgba(255,255,255,0.4); }
    .msg-modal-chars { color: rgba(255,255,255,0.4); font-size: 0.72rem; text-align: right; margin-top: 0.3rem; margin-bottom: 1.1rem; }
    .msg-modal-error { color: #e74c3c; font-size: 0.78rem; margin-top: -0.7rem; margin-bottom: 0.8rem; display: none; }
    .msg-modal-actions { display: flex; gap: 0.75rem; }
    .msg-modal-cancel { flex: 1; padding: 0.8rem; border-radius: 999px; border: 1px solid rgba(255,255,255,0.25); background: transparent; color: rgba(255,255,255,0.75); font-family: 'Poppins', sans-serif; font-size: 0.85rem; font-weight: 600; cursor: pointer; }
    .msg-modal-send { flex: 2; padding: 0.8rem; border-radius: 999px; border: none; background: #fff; color: #c0392b; font-family: 'Poppins', sans-serif; font-size: 0.85rem; font-weight: 700; cursor: pointer; }
    .report-modal textarea { min-height: 120px; }
    .report-modal-send { flex: 2; padding: 0.8rem; border-radius: 999px; border: none; background: linear-gradient(135deg, #ff7b72, #e74c3c); color: #fff; font-family: 'Poppins', sans-serif; font-size: 0.85rem; font-weight: 700; cursor: pointer; }
    .match-toast { position: fixed; top: 1.5rem; left: 50%; transform: translateX(-50%); background: linear-gradient(135deg, #2ecc71, #27ae60); color: #fff; font-weight: 700; font-size: 1rem; padding: 0.9rem 2rem; border-radius: 999px; box-shadow: 0 8px 30px rgba(0,0,0,0.3); z-index: 10000; animation: toastIn 0.3s ease; }
    .match-toast.info { background: linear-gradient(135deg, #4b5d73, #22313f); }
    @keyframes toastIn { from { opacity: 0; transform: translateX(-50%) translateY(-20px); } to { opacity: 1; transform: translateX(-50%) translateY(0); } }

    @media (max-width: 960px) { .discover-layout { grid-template-columns: 1fr; } }
    @media (max-width: 480px) { .swipe-container { width: 300px; height: 420px; } }
</style>
@endsection

@section('content')
<div class="discover-layout">
    <aside class="card filters-card">
        <div class="filter-section-title">
            <svg viewBox="0 0 24 24"><path d="M10 18h4v-2h-4v2zm-7-10v2h18V8H3zm3 7h12v-2H6v2z"/></svg>
            {{ __('app.filters') }}
        </div>

        <form method="GET" action="{{ route('home') }}" class="filter-form">
            <div>
                <label for="search">{{ __('app.search_label') }}</label>
                <input type="text" id="search" name="search" value="{{ $search }}" placeholder="{{ __('app.search_placeholder') }}">
            </div>

            <div class="filter-group">
                <button type="button" class="filter-group-toggle {{ count($selectedInterets) ? 'open' : '' }}" data-target="interestBody">
                    <span>{{ __('app.nav_interests') }}{{ count($selectedInterets) ? ' (' . count($selectedInterets) . ')' : '' }}</span>
                    <svg class="chevron" viewBox="0 0 24 24"><path d="M7 10l5 5 5-5z"/></svg>
                </button>
                <div class="filter-group-body {{ count($selectedInterets) ? 'open' : '' }}" id="interestBody">
                    <div class="interest-filter-grid">
                        @foreach ($interetsParCategorie as $categorie => $interets)
                            <div class="interest-sub-group">
                                <h4>{{ $categorie }}</h4>
                                <div class="interest-options">
                                    @foreach ($interets as $interet)
                                        <label class="interest-option">
                                            <input type="checkbox" name="interets[]" value="{{ $interet->id }}" {{ in_array($interet->id, $selectedInterets, true) ? 'checked' : '' }}>
                                            <span>{{ $interet->nom }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-filter">{{ __('app.filter_btn') }}</button>
                <a href="{{ route('home') }}" class="btn-reset">{{ __('app.clear_btn') }}</a>
            </div>
        </form>
    </aside>

    <section class="card swipe-section">
        <h2 class="swipe-title">{{ __('app.discover_profiles') }}</h2>
        <div class="swipe-top-actions">
            <a href="{{ route('dashboard') }}" class="btn-utility">{{ $hasSwipeHistory ? __('app.history_back') : __('app.view_dashboard') }}</a>
            <a href="{{ route('dashboard') }}#tab-blocked" class="btn-utility">{{ __('app.blocked_users') }}</a>
        </div>

        @if ($usersToSwipe->count() > 0)
            <div class="swipe-container" id="swipeContainer">
                @foreach ($usersToSwipe->reverse() as $profile)
                    @php
                        $connexionLabels = [
                            'amitie'   => '&#128075; ' . __('app.connection_friendship'),
                            'activites'=> '&#127939; ' . __('app.connection_activities'),
                            'etudes'   => '&#128218; ' . __('app.connection_studies'),
                            'sorties'  => '&#127917; ' . __('app.connection_outings'),
                            'gaming'   => '&#127918; ' . __('app.connection_gaming'),
                        ];
                        $profileConnexions = $profile->type_connexion ?? [];
                        $score = $matchScores[$profile->id] ?? 0;
                        $scoreLabel = rtrim(rtrim(number_format($score, 1, '.', ''), '0'), '.');
                        $scoreCls = $score >= 4 ? 'high' : ($score >= 2 ? 'mid' : 'low');
                    @endphp
                    <div class="swipe-card" data-user-id="{{ $profile->id }}">
                        <span class="swipe-indicator like-indicator">OUI</span>
                        <span class="swipe-indicator nope-indicator">PASSER</span>
                        <div class="profile-avatar">
                            @if ($profile->avatar_url)
                                <img src="{{ route('media.public', ['path' => $profile->avatar_url]) }}" alt="Avatar" style="width:100%;height:100%;object-fit:cover;border-radius:50%;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            @else
                                <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                            @endif
                        </div>
                        <div class="profile-name">{{ $profile->prenom }} {{ $profile->nom }}</div>
                        <div class="profile-sub">{{ $profile->position === 'etudiant' ? __('app.student') : __('app.staff') }}@if($profile->numero_programme) &nbsp;&bull;&nbsp; {{ $profile->numero_programme }}@endif</div>
                        <span class="card-match-score {{ $scoreCls }}">&#10024; {{ $scoreLabel }} {{ __('app.match_score') }}</span>
                        @if ($profile->bio)
                            <div class="profile-bio">{{ $profile->bio }}</div>
                        @endif
                        @if (!empty($profileConnexions) || $profile->interets->isNotEmpty())
                            <div class="profile-tags">
                                @foreach ($profileConnexions as $c)
                                    <span class="profile-connexion">{!! $connexionLabels[$c] ?? e($c) !!}</span>
                                @endforeach
                                @foreach ($profile->interets->take(4) as $interet)
                                    <span class="profile-interest">{{ $interet->nom }}</span>
                                @endforeach
                            </div>
                        @endif
                        <div class="profile-actions">
                            <button type="button" class="profile-action-btn btn-report-profile">{{ __('app.report_btn') }}</button>
                            <button type="button" class="profile-action-btn danger btn-block-profile">{{ __('app.block_btn') }}</button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="swipe-buttons">
                <button class="swipe-btn btn-nope" id="btnNope" title="Passer"><svg viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg></button>
                <button class="swipe-btn btn-like" id="btnLike" title="Connecter"><svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg></button>
            </div>
        @else
            <div class="empty-state">
                <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                <h2>{{ __('app.no_profiles_title') }}</h2>
                <p>{{ __('app.no_profiles_subtitle') }}</p>
            </div>
        @endif
    </section>
</div>

<div class="msg-modal-overlay hidden" id="msgModal">
    <div class="msg-modal">
        <div class="msg-modal-title">{{ __('app.send_message_to') }} <span id="msgModalName"></span></div>
        <div class="msg-modal-sub">{{ __('app.message_modal_sub') }}</div>
        <textarea id="msgModalText" rows="3" maxlength="500" placeholder="{{ __('app.message_placeholder') }}"></textarea>
        <div class="msg-modal-chars"><span id="msgModalCount">0</span>/500</div>
        <div class="msg-modal-error" id="msgModalError">{{ __('app.message_required') }}</div>
        <div class="msg-modal-actions">
            <button class="msg-modal-cancel" id="msgModalCancel" type="button">{{ __('app.cancel') }}</button>
            <button class="msg-modal-send" id="msgModalSend" type="button">{{ __('app.send') }}</button>
        </div>
    </div>
</div>

<div class="msg-modal-overlay hidden" id="reportModal">
    <div class="msg-modal report-modal">
        <div class="msg-modal-title">{{ __('app.report_btn') }} <span id="reportModalName"></span></div>
        <div class="msg-modal-sub">{{ __('app.report_modal_desc') }}</div>
        <textarea id="reportModalText" maxlength="1000" placeholder="{{ __('app.report_placeholder') }}"></textarea>
        <div class="msg-modal-chars"><span id="reportModalCount">0</span>/1000</div>
        <div class="msg-modal-error" id="reportModalError">{{ __('app.report_required') }}</div>
        <div class="msg-modal-actions">
            <button class="msg-modal-cancel" id="reportModalCancel" type="button">{{ __('app.cancel') }}</button>
            <button class="report-modal-send" id="reportModalSend" type="button">{{ __('app.send_report') }}</button>
        </div>
    </div>
</div>

<div class="msg-modal-overlay hidden" id="blockModal">
    <div class="msg-modal report-modal">
        <div class="msg-modal-title">{{ __('app.block_btn') }} <span id="blockModalName"></span></div>
        <div class="msg-modal-sub">{{ __('app.block_modal_desc') }}</div>
        <div class="msg-modal-actions">
            <button class="msg-modal-cancel" id="blockModalCancel" type="button">{{ __('app.cancel') }}</button>
            <button class="report-modal-send" id="blockModalConfirm" type="button">{{ __('app.confirm_block') }}</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.filter-group-toggle').forEach(btn => {
        btn.addEventListener('click', function () {
            const body = document.getElementById(this.dataset.target);
            const open = body.classList.contains('open');
            body.classList.toggle('open', !open);
            this.classList.toggle('open', !open);
        });
    });

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const swipeContainer = document.getElementById('swipeContainer');
    const msgModal = document.getElementById('msgModal');
    const msgText = document.getElementById('msgModalText');
    const msgCount = document.getElementById('msgModalCount');
    const msgError = document.getElementById('msgModalError');
    const reportModal = document.getElementById('reportModal');
    const reportText = document.getElementById('reportModalText');
    const reportCount = document.getElementById('reportModalCount');
    const reportError = document.getElementById('reportModalError');
    const blockModal = document.getElementById('blockModal');

    let currentCard = null;
    let pendingLikeCard = null;
    let pendingReportCard = null;
    let pendingBlockCard = null;
    let startX = 0;
    let currentX = 0;
    let isDragging = false;

    function getVisibleCards() {
        return Array.from(document.querySelectorAll('.swipe-card:not(.swipe-left):not(.swipe-right)'));
    }

    function getTopCard() {
        const cards = getVisibleCards();
        return cards.length ? cards[cards.length - 1] : null;
    }

    function updateTopCard() {
        getVisibleCards().forEach(card => card.classList.remove('top-card'));
        const top = getTopCard();
        if (top) top.classList.add('top-card');
    }

    function showToast(text, tone = 'success') {
        const toast = document.createElement('div');
        toast.className = `match-toast${tone === 'info' ? ' info' : ''}`;
        toast.textContent = text;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    async function postJson(url, payload = {}) {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify(payload),
        });

        const data = await response.json().catch(() => ({}));
        if (!response.ok) throw new Error(data.error || data.message || 'Une erreur est survenue.');
        return data;
    }

    function doSwipeAnimation(card, direction) {
        if (!card) return;
        const visible = getVisibleCards();
        if (visible.length > 1) visible[visible.length - 2].classList.add('top-card');
        card.classList.add(direction === 'right' ? 'swipe-right' : 'swipe-left');
        card.classList.remove('top-card');
        setTimeout(() => {
            card.remove();
            updateTopCard();
        }, 400);
    }

    function openMessageModal(card) {
        pendingLikeCard = card;
        document.getElementById('msgModalName').textContent = card.querySelector('.profile-name').textContent.trim();
        msgText.value = '';
        msgCount.textContent = '0';
        msgError.style.display = 'none';
        msgModal.classList.remove('hidden');
        setTimeout(() => msgText.focus(), 50);
    }

    function closeMessageModal() {
        msgModal.classList.add('hidden');
        if (pendingLikeCard) {
            pendingLikeCard.style.transform = '';
            pendingLikeCard.querySelector('.like-indicator').style.opacity = 0;
        }
        pendingLikeCard = null;
    }

    function openReportModal(card) {
        pendingReportCard = card;
        document.getElementById('reportModalName').textContent = card.querySelector('.profile-name').textContent.trim();
        reportText.value = '';
        reportCount.textContent = '0';
        reportError.style.display = 'none';
        reportModal.classList.remove('hidden');
        setTimeout(() => reportText.focus(), 50);
    }

    function closeReportModal() {
        reportModal.classList.add('hidden');
        pendingReportCard = null;
    }

    function openBlockModal(card) {
        pendingBlockCard = card;
        document.getElementById('blockModalName').textContent = card.querySelector('.profile-name').textContent.trim();
        blockModal.classList.remove('hidden');
    }

    function closeBlockModal() {
        blockModal.classList.add('hidden');
        pendingBlockCard = null;
    }

    async function submitLike(card, message) {
        doSwipeAnimation(card, 'right');
        try {
            const data = await postJson('/swipe', { user_id: card.dataset.userId, action: 'like', message });
            if (data.status === 'matched') showToast(data.message);
        } catch (error) {
            showToast(error.message, 'info');
        }
    }

    async function submitPass(card) {
        doSwipeAnimation(card, 'left');
        try {
            await postJson('/swipe', { user_id: card.dataset.userId, action: 'pass' });
        } catch (error) {
            showToast(error.message, 'info');
        }
    }

    async function confirmBlock(card) {
        try {
            const data = await postJson(`/profiles/${card.dataset.userId}/block`);
            closeBlockModal();
            doSwipeAnimation(card, 'left');
            showToast(data.message, 'info');
        } catch (error) {
            closeBlockModal();
            showToast(error.message, 'info');
        }
    }

    function swipeCard(direction) {
        const card = getTopCard();
        if (!card) return;
        if (direction === 'right') return openMessageModal(card);
        submitPass(card);
    }

    msgText?.addEventListener('input', () => {
        msgCount.textContent = msgText.value.length;
        if (msgText.value.trim()) msgError.style.display = 'none';
    });

    reportText?.addEventListener('input', () => {
        reportCount.textContent = reportText.value.length;
        if (reportText.value.trim()) reportError.style.display = 'none';
    });

    document.getElementById('msgModalSend')?.addEventListener('click', () => {
        const message = msgText.value.trim();
        if (!message) {
            msgError.style.display = 'block';
            return;
        }

        const card = pendingLikeCard;
        closeMessageModal();
        if (card) submitLike(card, message);
    });

    document.getElementById('msgModalCancel')?.addEventListener('click', closeMessageModal);
    document.getElementById('reportModalCancel')?.addEventListener('click', closeReportModal);
    document.getElementById('blockModalCancel')?.addEventListener('click', closeBlockModal);
    document.getElementById('blockModalConfirm')?.addEventListener('click', () => {
        if (pendingBlockCard) confirmBlock(pendingBlockCard);
    });

    document.getElementById('reportModalSend')?.addEventListener('click', async () => {
        const reason = reportText.value.trim();
        if (!reason) {
            reportError.style.display = 'block';
            return;
        }

        const card = pendingReportCard;
        if (!card) return;

        try {
            const data = await postJson(`/profiles/${card.dataset.userId}/report`, { reason });
            closeReportModal();
            showToast(data.message, 'info');
        } catch (error) {
            reportError.textContent = error.message;
            reportError.style.display = 'block';
        }
    });

    msgModal?.addEventListener('click', event => {
        if (event.target === msgModal) closeMessageModal();
    });

    reportModal?.addEventListener('click', event => {
        if (event.target === reportModal) closeReportModal();
    });

    blockModal?.addEventListener('click', event => {
        if (event.target === blockModal) closeBlockModal();
    });

    document.getElementById('btnNope')?.addEventListener('click', () => swipeCard('left'));
    document.getElementById('btnLike')?.addEventListener('click', () => swipeCard('right'));

    swipeContainer?.addEventListener('click', event => {
        const reportButton = event.target.closest('.btn-report-profile');
        if (reportButton) {
            const card = reportButton.closest('.swipe-card');
            if (card) openReportModal(card);
            return;
        }

        const blockButton = event.target.closest('.btn-block-profile');
        if (blockButton) {
            const card = blockButton.closest('.swipe-card');
            if (card) openBlockModal(card);
        }
    });

    document.addEventListener('pointerdown', event => {
        currentCard = getTopCard();
        if (!currentCard || !currentCard.contains(event.target) || event.target.closest('.profile-action-btn')) return;
        isDragging = true;
        startX = event.clientX;
        currentCard.classList.add('swiping');
        currentCard.setPointerCapture(event.pointerId);
    });

    document.addEventListener('pointermove', event => {
        if (!isDragging || !currentCard) return;
        currentX = event.clientX - startX;
        currentCard.style.transform = `translateX(${currentX}px) rotate(${currentX * 0.08}deg)`;
        const like = currentCard.querySelector('.like-indicator');
        const nope = currentCard.querySelector('.nope-indicator');
        const opacity = Math.min(Math.abs(currentX) / 100, 1);
        if (currentX > 0) {
            like.style.opacity = opacity;
            nope.style.opacity = 0;
        } else {
            nope.style.opacity = opacity;
            like.style.opacity = 0;
        }
    });

    document.addEventListener('pointerup', () => {
        if (!isDragging || !currentCard) return;
        isDragging = false;
        currentCard.classList.remove('swiping');
        const like = currentCard.querySelector('.like-indicator');
        const nope = currentCard.querySelector('.nope-indicator');
        if (currentX > 100) swipeCard('right');
        else if (currentX < -100) swipeCard('left');
        else {
            currentCard.style.transform = '';
            like.style.opacity = 0;
            nope.style.opacity = 0;
        }
        currentCard = null;
        currentX = 0;
    });

    updateTopCard();
</script>
@endsection
