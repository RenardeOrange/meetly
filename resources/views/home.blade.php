@extends('layouts.app')

@section('title', 'Decouvrir')

@section('styles')
<style>
    /* ── Layout ── */
    .discover-layout { display: grid; grid-template-columns: 300px minmax(0, 1fr); gap: 1.5rem; align-items: start; }

    /* ── Filter sidebar ── */
    .filters-card { padding: 1.2rem; }
    .filter-section-title { color: #fff; font-size: 1rem; font-weight: 700; margin-bottom: 0.8rem; display: flex; align-items: center; gap: 0.5rem; }
    .filter-section-title svg { width: 18px; height: 18px; fill: currentColor; }
    .filter-form { display: grid; gap: 1.1rem; }
    .filter-form label { display: block; color: rgba(255,255,255,0.85); font-size: 0.8rem; font-weight: 600; margin-bottom: 0.3rem; }
    .filter-form input[type="text"], .filter-form select { width: 100%; padding: 0.75rem 0.9rem; border-radius: 14px; border: 1px solid rgba(255,255,255,0.22); background: rgba(255,255,255,0.12); color: #fff; outline: none; font-family: 'Poppins', sans-serif; font-size: 0.85rem; }
    .filter-form input::placeholder { color: rgba(255,255,255,0.5); }
    .filter-form select option { color: #222; }

    /* Collapsible sub-sections */
    .filter-group { background: rgba(255,255,255,0.06); border-radius: 14px; overflow: hidden; }
    .filter-group-toggle { width: 100%; display: flex; align-items: center; justify-content: space-between; padding: 0.7rem 0.9rem; background: none; border: none; color: #fff; font-family: 'Poppins', sans-serif; font-size: 0.82rem; font-weight: 600; cursor: pointer; }
    .filter-group-toggle .chevron { width: 16px; height: 16px; fill: currentColor; transition: transform 0.25s; }
    .filter-group-toggle.open .chevron { transform: rotate(180deg); }
    .filter-group-body { max-height: 0; overflow: hidden; transition: max-height 0.3s ease; padding: 0 0.9rem; }
    .filter-group-body.open { max-height: 600px; padding: 0 0.9rem 0.9rem; }

    .interest-filter-grid { display: grid; gap: 0.65rem; }
    .interest-sub-group { }
    .interest-sub-group h4 { color: rgba(255,255,255,0.7); font-size: 0.74rem; font-weight: 600; margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.05em; }
    .interest-options { display: flex; flex-wrap: wrap; gap: 0.4rem; }
    .interest-option { position: relative; }
    .interest-option input { position: absolute; opacity: 0; }
    .interest-option span { display: inline-flex; align-items: center; padding: 0.35rem 0.7rem; border-radius: 999px; background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.8); font-size: 0.74rem; cursor: pointer; }
    .interest-option input:checked + span { background: #fff; color: #c0392b; font-weight: 700; }

    .filter-actions { display: flex; gap: 0.6rem; }
    .btn-filter, .btn-reset { flex: 1; border: none; border-radius: 999px; padding: 0.8rem 1rem; font-weight: 700; text-decoration: none; text-align: center; cursor: pointer; font-family: 'Poppins', sans-serif; font-size: 0.85rem; }
    .btn-filter { background: #fff; color: #c0392b; }
    .btn-reset { background: rgba(255,255,255,0.14); color: #fff; }

    /* Profile incomplete notice */
    .notice { padding: 0.7rem 0.9rem; border-radius: 12px; background: rgba(241,196,15,0.15); border: 1px solid rgba(241,196,15,0.35); color: rgba(255,255,255,0.9); font-size: 0.78rem; line-height: 1.5; }
    .notice a { color: #f1c40f; font-weight: 600; }

    /* ── Swipe section ── */
    .swipe-section { display: flex; flex-direction: column; align-items: center; gap: 1.2rem; padding: 1.4rem; }
    .swipe-title { color: #fff; font-size: 1.25rem; font-weight: 700; text-align: center; }
    .swipe-container { position: relative; width: 340px; height: 440px; }

    /* Only the top card is visible — others hidden */
    .swipe-card { position: absolute; width: 100%; height: 100%; background: rgba(255,255,255,0.15); backdrop-filter: blur(20px); border: 2px solid rgba(255,255,255,0.25); border-radius: 24px; padding: 2rem; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; cursor: grab; user-select: none; transition: transform 0.4s ease, opacity 0.4s ease; will-change: transform; visibility: hidden; pointer-events: none; }
    .swipe-card.top-card { visibility: visible; pointer-events: auto; }
    .swipe-card.swiping { transition: none; cursor: grabbing; }
    .swipe-card.swipe-right { transform: translateX(150%) rotate(20deg); opacity: 0; visibility: visible; }
    .swipe-card.swipe-left { transform: translateX(-150%) rotate(-20deg); opacity: 0; visibility: visible; }

    .profile-avatar { width: 100px; height: 100px; border-radius: 50%; background: rgba(255,255,255,0.25); display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem; overflow: hidden; }
    .profile-avatar svg { width: 50px; height: 50px; fill: rgba(255,255,255,0.8); }
    .profile-name { color: #fff; font-size: 1.35rem; font-weight: 700; margin-bottom: 0.25rem; }
    .profile-position { color: rgba(255,255,255,0.7); font-size: 0.82rem; margin-bottom: 0.3rem; text-transform: capitalize; }
    .profile-bio { color: rgba(255,255,255,0.85); font-size: 0.85rem; line-height: 1.5; max-height: 80px; overflow: hidden; margin-top: 0.25rem; }
    .swipe-indicator { position: absolute; top: 1.5rem; font-size: 1.1rem; font-weight: 700; padding: 0.3rem 1rem; border-radius: 8px; opacity: 0; transition: opacity 0.2s ease; }
    .like-indicator { right: 1.5rem; color: #2ecc71; border: 2px solid #2ecc71; }
    .nope-indicator { left: 1.5rem; color: #e74c3c; border: 2px solid #e74c3c; }
    .profile-interest-row { display: flex; flex-wrap: wrap; gap: 0.4rem; margin-top: 0.9rem; justify-content: center; }
    .profile-interest { padding: 0.3rem 0.6rem; border-radius: 999px; background: rgba(255,255,255,0.16); color: #fff; font-size: 0.72rem; }
    .profile-relation-row { display: flex; flex-wrap: wrap; gap: 0.35rem; margin-top: 0.5rem; justify-content: center; }
    .profile-relation { padding: 0.25rem 0.55rem; border-radius: 999px; background: rgba(255,200,100,0.2); color: rgba(255,230,150,0.95); font-size: 0.68rem; border: 1px solid rgba(255,200,100,0.3); }
    .swipe-buttons { display: flex; gap: 1.5rem; margin-top: 0.5rem; }
    .swipe-btn { width: 60px; height: 60px; border-radius: 50%; border: 2px solid rgba(255,255,255,0.5); background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); display: flex; align-items: center; justify-content: center; cursor: pointer; }
    .swipe-btn svg { width: 28px; height: 28px; }
    .btn-nope svg { fill: #e74c3c; }
    .btn-like svg { fill: #2ecc71; }
    .empty-state { text-align: center; color: rgba(255,255,255,0.8); padding: 3rem 1rem; }
    .empty-state svg { width: 80px; height: 80px; fill: rgba(255,255,255,0.3); margin-bottom: 1rem; }
    .empty-state h2 { color: #fff; margin-bottom: 0.5rem; }

    @media (max-width: 960px) { .discover-layout { grid-template-columns: 1fr; } }
    @media (max-width: 480px) { .swipe-container { width: 300px; height: 400px; } }
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

        @if (!auth()->user()->genre || !auth()->user()->orientation)
            <div class="notice" style="margin-bottom:1rem;">
                Configurez votre <a href="{{ route('profile.edit') }}">genre et orientation</a> pour des suggestions personnalis&eacute;es.
            </div>
        @endif

        <form method="GET" action="{{ route('home') }}" class="filter-form">

            {{-- Search --}}
            <div>
                <label for="search">Recherche</label>
                <input type="text" id="search" name="search" value="{{ $search }}" placeholder="Nom, bio ou programme">
            </div>

            {{-- Interests collapsible --}}
            <div class="filter-group">
                <button type="button" class="filter-group-toggle {{ count($selectedInterets) ? 'open' : '' }}" data-target="interestBody">
                    <span>Int&eacute;r&ecirc;ts requis{{ count($selectedInterets) ? ' (' . count($selectedInterets) . ')' : '' }}</span>
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
        <h2 class="swipe-title">D&eacute;couvrir des profils compatibles</h2>
        @if($usersToSwipe->count() > 0)
            <div class="swipe-container" id="swipeContainer">
                @foreach($usersToSwipe->reverse() as $profile)
                    @php
                        $relationLabels = [
                            'amitie'              => 'Amitie',
                            'romantique_serieux'  => 'Relation serieuse',
                            'romantique_casual'   => 'Relation casual',
                            'activites'           => 'Partenaire d\'activites',
                        ];
                        $profileRelations = $profile->type_relation ?? [];
                    @endphp
                    <div class="swipe-card" data-user-id="{{ $profile->id }}">
                        <span class="swipe-indicator like-indicator">LIKE</span>
                        <span class="swipe-indicator nope-indicator">NOPE</span>
                        <div class="profile-avatar">
                            @if ($profile->avatar_url)
                                <img src="{{ asset('storage/' . $profile->avatar_url) }}" alt="Avatar" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                            @else
                                <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                            @endif
                        </div>
                        <div class="profile-name">{{ $profile->prenom }} {{ $profile->nom }}</div>
                        <div class="profile-position">{{ $profile->position === 'etudiant' ? 'Etudiant(e)' : 'Personnel' }}@if($profile->genre) &nbsp;&bull;&nbsp; {{ ucfirst($profile->genre) }}@endif</div>
                        @if($profile->numero_programme)<div class="profile-position">{{ $profile->numero_programme }}</div>@endif
                        @if($profile->bio)<div class="profile-bio">{{ $profile->bio }}</div>@endif
                        @if(!empty($profileRelations))
                            <div class="profile-relation-row">
                                @foreach($profileRelations as $rel)
                                    <span class="profile-relation">{{ $relationLabels[$rel] ?? $rel }}</span>
                                @endforeach
                            </div>
                        @endif
                        @if($profile->interets->isNotEmpty())
                            <div class="profile-interest-row">
                                @foreach($profile->interets->take(5) as $interet)
                                    <span class="profile-interest">{{ $interet->nom }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="swipe-buttons">
                <button class="swipe-btn btn-nope" id="btnNope" title="Passer"><svg viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg></button>
                <button class="swipe-btn btn-like" id="btnLike" title="Aimer"><svg viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg></button>
            </div>
        @else
            <div class="empty-state">
                <svg viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                <h2>Aucun profil disponible</h2>
                <p>Ajuste les filtres ou reviens un peu plus tard.</p>
            </div>
        @endif
    </section>
</div>
@endsection

@section('scripts')
<script>
    // ── Collapsible filter groups ──
    document.querySelectorAll('.filter-group-toggle').forEach(btn => {
        btn.addEventListener('click', function () {
            const targetId = this.dataset.target;
            const body = document.getElementById(targetId);
            const isOpen = body.classList.contains('open');
            body.classList.toggle('open', !isOpen);
            this.classList.toggle('open', !isOpen);
        });
    });

    // ── Swipe logic ──
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    let currentCard = null, startX = 0, currentX = 0, isDragging = false;

    function getTopCard() {
        const cards = document.querySelectorAll('.swipe-card:not(.swipe-left):not(.swipe-right)');
        return cards.length ? cards[cards.length - 1] : null;
    }

    function updateTopCard() {
        const cards = document.querySelectorAll('.swipe-card:not(.swipe-left):not(.swipe-right)');
        document.querySelectorAll('.swipe-card').forEach(c => {
            if (!c.classList.contains('swipe-left') && !c.classList.contains('swipe-right')) {
                c.classList.remove('top-card');
            }
        });
        if (cards.length > 0) {
            cards[cards.length - 1].classList.add('top-card');
        }
    }

    function swipeCard(direction) {
        const card = getTopCard();
        if (!card) return;
        const userId = card.dataset.userId;

        // Reveal the next card before the animation
        const allVisible = Array.from(document.querySelectorAll('.swipe-card:not(.swipe-left):not(.swipe-right)'));
        if (allVisible.length > 1) {
            allVisible[allVisible.length - 2].classList.add('top-card');
        }

        card.classList.add(direction === 'right' ? 'swipe-right' : 'swipe-left');
        card.classList.remove('top-card');

        fetch('/swipe', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ user_id: userId, action: direction === 'right' ? 'like' : 'pass' })
        });

        setTimeout(() => { card.remove(); updateTopCard(); }, 400);
    }

    document.getElementById('btnNope')?.addEventListener('click', () => swipeCard('left'));
    document.getElementById('btnLike')?.addEventListener('click', () => swipeCard('right'));

    document.addEventListener('pointerdown', (e) => {
        currentCard = getTopCard();
        if (!currentCard || !currentCard.contains(e.target)) return;
        isDragging = true; startX = e.clientX;
        currentCard.classList.add('swiping');
        currentCard.setPointerCapture(e.pointerId);
    });

    document.addEventListener('pointermove', (e) => {
        if (!isDragging || !currentCard) return;
        currentX = e.clientX - startX;
        currentCard.style.transform = `translateX(${currentX}px) rotate(${currentX * 0.08}deg)`;
        const likeInd = currentCard.querySelector('.like-indicator');
        const nopeInd = currentCard.querySelector('.nope-indicator');
        const opacity = Math.min(Math.abs(currentX) / 100, 1);
        if (currentX > 0) { likeInd.style.opacity = opacity; nopeInd.style.opacity = 0; }
        else { nopeInd.style.opacity = opacity; likeInd.style.opacity = 0; }
    });

    document.addEventListener('pointerup', () => {
        if (!isDragging || !currentCard) return;
        isDragging = false;
        currentCard.classList.remove('swiping');
        const likeInd = currentCard.querySelector('.like-indicator');
        const nopeInd = currentCard.querySelector('.nope-indicator');
        if (currentX > 100) swipeCard('right');
        else if (currentX < -100) swipeCard('left');
        else {
            currentCard.style.transform = '';
            likeInd.style.opacity = 0; nopeInd.style.opacity = 0;
        }
        currentCard = null; currentX = 0;
    });

    // Initialize: show only top card
    updateTopCard();
</script>
@endsection
