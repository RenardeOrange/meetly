@extends('layouts.app')

@section('title', 'Découvrir')

@section('styles')
<style>
    /* ── Layout ── */
    .discover-layout { display: grid; grid-template-columns: 290px minmax(0, 1fr); gap: 1.5rem; align-items: start; }

    /* ── Filter sidebar ── */
    .filters-card { padding: 1.2rem; }
    .filter-section-title { color: #fff; font-size: 1rem; font-weight: 700; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; }
    .filter-section-title svg { width: 18px; height: 18px; fill: currentColor; flex-shrink: 0; }
    .filter-form { display: grid; gap: 1rem; }
    .filter-form > label { display: block; color: rgba(255,255,255,0.85); font-size: 0.8rem; font-weight: 600; margin-bottom: 0.3rem; }
    .filter-form input[type="text"] { width: 100%; padding: 0.75rem 0.9rem; border-radius: 14px; border: 1px solid rgba(255,255,255,0.22); background: rgba(255,255,255,0.12); color: #fff; outline: none; font-family: 'Poppins', sans-serif; font-size: 0.85rem; }
    .filter-form input::placeholder { color: rgba(255,255,255,0.5); }

    /* Collapsible interest section */
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

    /* ── Swipe section ── */
    .swipe-section { display: flex; flex-direction: column; align-items: center; gap: 1.2rem; padding: 1.4rem; }
    .swipe-title { color: #fff; font-size: 1.2rem; font-weight: 700; text-align: center; }
    .swipe-container { position: relative; width: 340px; height: 460px; }

    /* Only top card visible */
    .swipe-card { position: absolute; width: 100%; height: 100%; background: rgba(255,255,255,0.15); backdrop-filter: blur(20px); border: 2px solid rgba(255,255,255,0.25); border-radius: 24px; padding: 1.75rem 1.5rem; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; cursor: grab; user-select: none; transition: transform 0.4s ease, opacity 0.4s ease; will-change: transform; visibility: hidden; pointer-events: none; }
    .swipe-card.top-card { visibility: visible; pointer-events: auto; }
    .swipe-card.swiping { transition: none; cursor: grabbing; }
    .swipe-card.swipe-right { transform: translateX(150%) rotate(20deg); opacity: 0; visibility: visible; }
    .swipe-card.swipe-left  { transform: translateX(-150%) rotate(-20deg); opacity: 0; visibility: visible; }

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
    .swipe-buttons { display: flex; gap: 1.5rem; margin-top: 0.4rem; }
    .swipe-btn { width: 60px; height: 60px; border-radius: 50%; border: 2px solid rgba(255,255,255,0.5); background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); display: flex; align-items: center; justify-content: center; cursor: pointer; }
    .swipe-btn svg { width: 28px; height: 28px; }
    .btn-nope svg { fill: #e74c3c; }
    .btn-like svg { fill: #2ecc71; }
    .empty-state { text-align: center; color: rgba(255,255,255,0.8); padding: 3rem 1rem; }
    .empty-state svg { width: 80px; height: 80px; fill: rgba(255,255,255,0.3); margin-bottom: 1rem; }
    .empty-state h2 { color: #fff; margin-bottom: 0.5rem; }

    /* ── Match score pill on swipe card ── */
    .card-match-score { display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.28rem 0.7rem; border-radius: 999px; font-size: 0.75rem; font-weight: 700; margin-top: 0.6rem; }
    .card-match-score.high  { background: rgba(46,204,113,0.22); color: #2ecc71; border: 1px solid rgba(46,204,113,0.45); }
    .card-match-score.mid   { background: rgba(253,216,53,0.18); color: #fdd835; border: 1px solid rgba(253,216,53,0.4); }
    .card-match-score.low   { background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.6); border: 1px solid rgba(255,255,255,0.18); }

    @media (max-width: 960px) { .discover-layout { grid-template-columns: 1fr; } }
    @media (max-width: 480px) { .swipe-container { width: 300px; height: 420px; } }

    /* ── Message request modal ── */
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
    .msg-modal-send:disabled { opacity: 0.5; cursor: not-allowed; }

    /* ── Match toast ── */
    .match-toast { position: fixed; top: 1.5rem; left: 50%; transform: translateX(-50%); background: linear-gradient(135deg, #2ecc71, #27ae60); color: #fff; font-weight: 700; font-size: 1rem; padding: 0.9rem 2rem; border-radius: 999px; box-shadow: 0 8px 30px rgba(0,0,0,0.3); z-index: 10000; animation: toastIn 0.3s ease; }
    @keyframes toastIn { from { opacity: 0; transform: translateX(-50%) translateY(-20px); } to { opacity: 1; transform: translateX(-50%) translateY(0); } }
</style>
@endsection

@section('content')
<div class="discover-layout">

    {{-- ── Filter sidebar ── --}}
    <aside class="card filters-card">
        <div class="filter-section-title">
            <svg viewBox="0 0 24 24"><path d="M10 18h4v-2h-4v2zm-7-10v2h18V8H3zm3 7h12v-2H6v2z"/></svg>
            Filtres
        </div>

        <form method="GET" action="{{ route('home') }}" class="filter-form">
            <div>
                <label for="search">Recherche</label>
                <input type="text" id="search" name="search" value="{{ $search }}" placeholder="Nom, bio ou programme">
            </div>

            <div class="filter-group">
                <button type="button" class="filter-group-toggle {{ count($selectedInterets) ? 'open' : '' }}" data-target="interestBody">
                    <span>Int&eacute;r&ecirc;ts{{ count($selectedInterets) ? ' (' . count($selectedInterets) . ')' : '' }}</span>
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
                <button type="submit" class="btn-filter">Filtrer</button>
                <a href="{{ route('home') }}" class="btn-reset">Effacer</a>
            </div>
        </form>
    </aside>

    {{-- ── Swipe section ── --}}
    <section class="card swipe-section">
        <h2 class="swipe-title">D&eacute;couvrir des profils</h2>
        @if($usersToSwipe->count() > 0)
            <div class="swipe-container" id="swipeContainer">
                @foreach($usersToSwipe->reverse() as $profile)
                    @php
                        $connexionLabels = [
                            'amitie'    => '&#128075; Amiti&eacute;',
                            'activites' => '&#127939; Activit&eacute;s',
                            'etudes'    => '&#128218; &Eacute;tudes',
                            'sorties'   => '&#127917; Sorties',
                            'gaming'    => '&#127918; Gaming',
                        ];
                        $profileConnexions = $profile->type_connexion ?? [];
                        $score = $matchScores[$profile->id] ?? 0;
                        $scoreCls = $score >= 60 ? 'high' : ($score >= 30 ? 'mid' : 'low');
                    @endphp
                    <div class="swipe-card" data-user-id="{{ $profile->id }}">
                        <span class="swipe-indicator like-indicator">OUI</span>
                        <span class="swipe-indicator nope-indicator">PASSER</span>
                        <div class="profile-avatar">
                            @if ($profile->avatar_url)
                                <img src="{{ asset('storage/' . $profile->avatar_url) }}" alt="Avatar" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                            @else
                                <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                            @endif
                        </div>
                        <div class="profile-name">{{ $profile->prenom }} {{ $profile->nom }}</div>
                        <div class="profile-sub">{{ $profile->position === 'etudiant' ? 'Étudiant(e)' : 'Personnel' }}@if($profile->numero_programme) &nbsp;&bull;&nbsp; {{ $profile->numero_programme }}@endif</div>
                        <span class="card-match-score {{ $scoreCls }}">&#10024; {{ $score }}% en commun</span>
                        @if($profile->bio)<div class="profile-bio">{{ $profile->bio }}</div>@endif
                        @if(!empty($profileConnexions) || $profile->interets->isNotEmpty())
                            <div class="profile-tags">
                                @foreach($profileConnexions as $c)
                                    <span class="profile-connexion">{!! $connexionLabels[$c] ?? $c !!}</span>
                                @endforeach
                                @foreach($profile->interets->take(4) as $interet)
                                    <span class="profile-interest">{{ $interet->nom }}</span>
                                @endforeach
                            </div>
                        @endif
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
                <h2>Aucun profil disponible</h2>
                <p>Ajuste les filtres ou reviens un peu plus tard.</p>
            </div>
        @endif
    </section>
</div>

{{-- ── Message request modal ── --}}
<div class="msg-modal-overlay hidden" id="msgModal">
    <div class="msg-modal">
        <div class="msg-modal-title">Envoie un message à <span id="msgModalName"></span></div>
        <div class="msg-modal-sub">Ils verront ton message si tu les intéresses.</div>
        <textarea id="msgModalText" rows="3" maxlength="500" placeholder="Dis quelque chose de sympa..."></textarea>
        <div class="msg-modal-chars"><span id="msgModalCount">0</span>/500</div>
        <div class="msg-modal-error" id="msgModalError">Écris un petit message avant d'envoyer.</div>
        <div class="msg-modal-actions">
            <button class="msg-modal-cancel" id="msgModalCancel">Annuler</button>
            <button class="msg-modal-send" id="msgModalSend">Envoyer ✓</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Collapsible filter groups
    document.querySelectorAll('.filter-group-toggle').forEach(btn => {
        btn.addEventListener('click', function () {
            const body = document.getElementById(this.dataset.target);
            const open = body.classList.contains('open');
            body.classList.toggle('open', !open);
            this.classList.toggle('open', !open);
        });
    });

    // Swipe
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    let currentCard = null, startX = 0, currentX = 0, isDragging = false;
    let pendingLikeCard = null;

    function getTopCard() {
        const cards = document.querySelectorAll('.swipe-card:not(.swipe-left):not(.swipe-right)');
        return cards.length ? cards[cards.length - 1] : null;
    }

    function updateTopCard() {
        document.querySelectorAll('.swipe-card:not(.swipe-left):not(.swipe-right)').forEach(c => c.classList.remove('top-card'));
        const top = getTopCard();
        if (top) top.classList.add('top-card');
    }

    function showMatchToast(text) {
        const t = document.createElement('div');
        t.className = 'match-toast';
        t.textContent = text;
        document.body.appendChild(t);
        setTimeout(() => t.remove(), 3000);
    }

    function doSwipeAnimation(card, direction) {
        const visible = Array.from(document.querySelectorAll('.swipe-card:not(.swipe-left):not(.swipe-right)'));
        if (visible.length > 1) visible[visible.length - 2].classList.add('top-card');
        card.classList.add(direction === 'right' ? 'swipe-right' : 'swipe-left');
        card.classList.remove('top-card');
        setTimeout(() => { card.remove(); updateTopCard(); }, 400);
    }

    function submitLike(card, message) {
        const userId = card.dataset.userId;
        doSwipeAnimation(card, 'right');
        fetch('/swipe', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ user_id: userId, action: 'like', message })
        }).then(r => r.json()).then(data => {
            if (data.status === 'matched') showMatchToast('🎉 ' + data.message);
        });
    }

    function swipeCard(direction) {
        const card = getTopCard();
        if (!card) return;

        if (direction === 'right') {
            // Show message modal instead of swiping immediately
            pendingLikeCard = card;
            document.getElementById('msgModalName').textContent = card.querySelector('.profile-name').textContent.trim();
            document.getElementById('msgModalText').value = '';
            document.getElementById('msgModalCount').textContent = '0';
            document.getElementById('msgModalError').style.display = 'none';
            document.getElementById('msgModal').classList.remove('hidden');
            setTimeout(() => document.getElementById('msgModalText').focus(), 50);
            return;
        }

        // Pass — animate and call backend
        doSwipeAnimation(card, 'left');
        fetch('/swipe', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ user_id: card.dataset.userId, action: 'pass' })
        });
    }

    // Modal logic
    const msgModal  = document.getElementById('msgModal');
    const msgText   = document.getElementById('msgModalText');
    const msgCount  = document.getElementById('msgModalCount');
    const msgError  = document.getElementById('msgModalError');

    msgText.addEventListener('input', () => {
        msgCount.textContent = msgText.value.length;
        if (msgText.value.trim()) msgError.style.display = 'none';
    });

    document.getElementById('msgModalSend').addEventListener('click', () => {
        const message = msgText.value.trim();
        if (!message) { msgError.style.display = 'block'; return; }
        msgModal.classList.add('hidden');
        submitLike(pendingLikeCard, message);
        pendingLikeCard = null;
    });

    document.getElementById('msgModalCancel').addEventListener('click', () => {
        msgModal.classList.add('hidden');
        // Reset card to centre
        if (pendingLikeCard) {
            pendingLikeCard.style.transform = '';
            pendingLikeCard.querySelector('.like-indicator').style.opacity = 0;
        }
        pendingLikeCard = null;
    });

    // Close on overlay click
    msgModal.addEventListener('click', e => {
        if (e.target === msgModal) document.getElementById('msgModalCancel').click();
    });

    document.getElementById('btnNope')?.addEventListener('click', () => swipeCard('left'));
    document.getElementById('btnLike')?.addEventListener('click', () => swipeCard('right'));

    document.addEventListener('pointerdown', e => {
        currentCard = getTopCard();
        if (!currentCard || !currentCard.contains(e.target)) return;
        isDragging = true; startX = e.clientX;
        currentCard.classList.add('swiping');
        currentCard.setPointerCapture(e.pointerId);
    });
    document.addEventListener('pointermove', e => {
        if (!isDragging || !currentCard) return;
        currentX = e.clientX - startX;
        currentCard.style.transform = `translateX(${currentX}px) rotate(${currentX * 0.08}deg)`;
        const like = currentCard.querySelector('.like-indicator');
        const nope = currentCard.querySelector('.nope-indicator');
        const op = Math.min(Math.abs(currentX) / 100, 1);
        if (currentX > 0) { like.style.opacity = op; nope.style.opacity = 0; }
        else { nope.style.opacity = op; like.style.opacity = 0; }
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
            like.style.opacity = 0; nope.style.opacity = 0;
        }
        currentCard = null; currentX = 0;
    });

    updateTopCard();
</script>
@endsection
