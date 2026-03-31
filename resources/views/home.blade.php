@extends('layouts.app')

@section('title', 'Decouvrir')

@section('styles')
<style>
    .discover-layout { display: grid; grid-template-columns: 320px minmax(0, 1fr); gap: 1.5rem; align-items: start; }
    .filters-card, .results-card { padding: 1.4rem; }
    .panel-title { color: #fff; font-size: 1.2rem; font-weight: 700; margin-bottom: 0.8rem; }
    .panel-subtitle { color: rgba(255, 255, 255, 0.72); font-size: 0.88rem; line-height: 1.5; margin-bottom: 1rem; }
    .filter-form { display: grid; gap: 1rem; }
    .filter-form label { display: block; color: rgba(255, 255, 255, 0.85); font-size: 0.82rem; font-weight: 600; margin-bottom: 0.35rem; }
    .filter-form input[type="text"] { width: 100%; padding: 0.85rem 1rem; border-radius: 16px; border: 1px solid rgba(255, 255, 255, 0.22); background: rgba(255, 255, 255, 0.12); color: #fff; outline: none; }
    .filter-form input::placeholder { color: rgba(255, 255, 255, 0.55); }
    .interest-filter-grid { display: grid; gap: 0.9rem; max-height: 420px; overflow: auto; padding-right: 0.35rem; }
    .interest-group { background: rgba(255, 255, 255, 0.08); border-radius: 16px; padding: 0.9rem; }
    .interest-group h3 { color: #fff; font-size: 0.88rem; margin-bottom: 0.7rem; }
    .interest-options { display: flex; flex-wrap: wrap; gap: 0.5rem; }
    .interest-option { position: relative; }
    .interest-option input { position: absolute; opacity: 0; }
    .interest-option span { display: inline-flex; align-items: center; padding: 0.45rem 0.8rem; border-radius: 999px; background: rgba(255, 255, 255, 0.12); color: rgba(255, 255, 255, 0.82); font-size: 0.78rem; cursor: pointer; }
    .interest-option input:checked + span { background: #fff; color: #c0392b; font-weight: 700; }
    .filter-actions { display: flex; gap: 0.75rem; }
    .btn-filter, .btn-reset { flex: 1; border: none; border-radius: 999px; padding: 0.85rem 1rem; font-weight: 700; text-decoration: none; text-align: center; cursor: pointer; }
    .btn-filter { background: #fff; color: #c0392b; }
    .btn-reset { background: rgba(255, 255, 255, 0.14); color: #fff; }
    .swipe-section { display: flex; flex-direction: column; align-items: center; gap: 1.2rem; }
    .swipe-title { color: #fff; font-size: 1.35rem; font-weight: 700; text-align: center; }
    .swipe-container { position: relative; width: 340px; height: 440px; }
    .swipe-card { position: absolute; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(20px); border: 2px solid rgba(255, 255, 255, 0.25); border-radius: 24px; padding: 2rem; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; cursor: grab; user-select: none; transition: transform 0.4s ease, opacity 0.4s ease, box-shadow 0.3s ease; will-change: transform; }
    .swipe-card.swiping { transition: none; cursor: grabbing; }
    .swipe-card.swipe-right { transform: translateX(150%) rotate(20deg); opacity: 0; }
    .swipe-card.swipe-left { transform: translateX(-150%) rotate(-20deg); opacity: 0; }
    .profile-avatar { width: 100px; height: 100px; border-radius: 50%; background: rgba(255, 255, 255, 0.25); display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem; overflow: hidden; }
    .profile-avatar svg { width: 50px; height: 50px; fill: rgba(255, 255, 255, 0.8); }
    .profile-name { color: #fff; font-size: 1.4rem; font-weight: 700; margin-bottom: 0.25rem; }
    .profile-position { color: rgba(255, 255, 255, 0.7); font-size: 0.85rem; margin-bottom: 0.5rem; text-transform: capitalize; }
    .profile-bio { color: rgba(255, 255, 255, 0.85); font-size: 0.88rem; line-height: 1.5; max-height: 80px; overflow: hidden; }
    .swipe-indicator { position: absolute; top: 1.5rem; font-size: 1.1rem; font-weight: 700; padding: 0.3rem 1rem; border-radius: 8px; opacity: 0; transition: opacity 0.2s ease; }
    .like-indicator { right: 1.5rem; color: #2ecc71; border: 2px solid #2ecc71; }
    .nope-indicator { left: 1.5rem; color: #e74c3c; border: 2px solid #e74c3c; }
    .profile-interest-row { display: flex; flex-wrap: wrap; gap: 0.45rem; margin-top: 1rem; justify-content: center; }
    .profile-interest { padding: 0.35rem 0.65rem; border-radius: 999px; background: rgba(255, 255, 255, 0.16); color: #fff; font-size: 0.75rem; }
    .swipe-buttons { display: flex; gap: 1.5rem; margin-top: 0.5rem; }
    .swipe-btn { width: 60px; height: 60px; border-radius: 50%; border: 2px solid rgba(255, 255, 255, 0.5); background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); display: flex; align-items: center; justify-content: center; cursor: pointer; }
    .swipe-btn svg { width: 28px; height: 28px; }
    .btn-nope svg { fill: #e74c3c; }
    .btn-like svg { fill: #2ecc71; }
    .empty-state { text-align: center; color: rgba(255, 255, 255, 0.8); padding: 3rem 1rem; }
    .empty-state svg { width: 80px; height: 80px; fill: rgba(255, 255, 255, 0.3); margin-bottom: 1rem; }
    .empty-state h2 { color: #fff; margin-bottom: 0.5rem; }
    @media (max-width: 960px) { .discover-layout { grid-template-columns: 1fr; } }
    @media (max-width: 480px) { .swipe-container { width: 300px; height: 400px; } }
</style>
@endsection

@section('content')
<div class="discover-layout">
    <aside class="card filters-card">
        <h1 class="panel-title">Recherche par interets</h1>
        <p class="panel-subtitle">Choisis un ou plusieurs interets. Les profils proposes devront tous les posseder.</p>
        <form method="GET" action="{{ route('home') }}" class="filter-form">
            <div><label for="search">Nom, bio ou programme</label><input type="text" id="search" name="search" value="{{ $search }}" placeholder="Ex. musique, TI, Julie"></div>
            <div>
                <label>Interets requis</label>
                <div class="interest-filter-grid">
                    @foreach ($interetsParCategorie as $categorie => $interets)
                        <div class="interest-group">
                            <h3>{{ $categorie }}</h3>
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
            <div class="filter-actions"><button type="submit" class="btn-filter">Filtrer</button><a href="{{ route('home') }}" class="btn-reset">Effacer</a></div>
        </form>
    </aside>

    <section class="card results-card">
        <div class="swipe-section">
            <h2 class="swipe-title">Decouvrir des profils compatibles</h2>
            @if($usersToSwipe->count() > 0)
                <div class="swipe-container" id="swipeContainer">
                    @foreach($usersToSwipe->reverse() as $profile)
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
                            <div class="profile-position">{{ $profile->position === 'etudiant' ? 'Etudiant(e)' : 'Personnel' }}</div>
                            @if($profile->numero_programme)<div class="profile-position">{{ $profile->numero_programme }}</div>@endif
                            @if($profile->bio)<div class="profile-bio">{{ $profile->bio }}</div>@endif
                            @if($profile->interets->isNotEmpty())
                                <div class="profile-interest-row">
                                    @foreach($profile->interets->take(6) as $interet)
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
                    <p>Ajuste les interets recherches ou reviens un peu plus tard.</p>
                </div>
            @endif
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    let currentCard = null, startX = 0, currentX = 0, isDragging = false;
    function getTopCard() { const cards = document.querySelectorAll('.swipe-card:not(.swipe-left):not(.swipe-right)'); return cards.length ? cards[cards.length - 1] : null; }
    function swipeCard(direction) {
        const card = getTopCard(); if (!card) return;
        const userId = card.dataset.userId;
        card.classList.add(direction === 'right' ? 'swipe-right' : 'swipe-left');
        fetch('/swipe', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }, body: JSON.stringify({ user_id: userId, action: direction === 'right' ? 'like' : 'pass' }) });
        setTimeout(() => { card.remove(); }, 400);
    }
    document.getElementById('btnNope')?.addEventListener('click', () => swipeCard('left'));
    document.getElementById('btnLike')?.addEventListener('click', () => swipeCard('right'));
    document.addEventListener('pointerdown', (e) => { currentCard = getTopCard(); if (!currentCard || !currentCard.contains(e.target)) return; isDragging = true; startX = e.clientX; currentCard.classList.add('swiping'); currentCard.setPointerCapture(e.pointerId); });
    document.addEventListener('pointermove', (e) => { if (!isDragging || !currentCard) return; currentX = e.clientX - startX; currentCard.style.transform = `translateX(${currentX}px) rotate(${currentX * 0.08}deg)`; const likeIndicator = currentCard.querySelector('.like-indicator'); const nopeIndicator = currentCard.querySelector('.nope-indicator'); const opacity = Math.min(Math.abs(currentX) / 100, 1); if (currentX > 0) { likeIndicator.style.opacity = opacity; nopeIndicator.style.opacity = 0; } else { nopeIndicator.style.opacity = opacity; likeIndicator.style.opacity = 0; } });
    document.addEventListener('pointerup', () => { if (!isDragging || !currentCard) return; isDragging = false; currentCard.classList.remove('swiping'); const likeIndicator = currentCard.querySelector('.like-indicator'); const nopeIndicator = currentCard.querySelector('.nope-indicator'); if (currentX > 100) swipeCard('right'); else if (currentX < -100) swipeCard('left'); else { currentCard.style.transform = ''; likeIndicator.style.opacity = 0; nopeIndicator.style.opacity = 0; } currentCard = null; currentX = 0; });
</script>
@endsection
