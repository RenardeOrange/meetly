@extends('layouts.app')

@section('title', 'Mes intérêts')

@section('styles')
<style>
    .interests-page { display: grid; gap: 1.5rem; max-width: 860px; margin: 0 auto; }

    /* ── Panels ── */
    .panel { padding: 1.4rem; }
    .panel-title { color: #fff; font-size: 1.05rem; font-weight: 700; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.65rem; }
    .panel-title .count-badge { font-size: 0.76rem; font-weight: 600; background: rgba(255,255,255,0.14); color: rgba(255,255,255,0.75); padding: 0.2rem 0.55rem; border-radius: 999px; }
    .empty-copy { color: rgba(255,255,255,0.6); font-size: 0.86rem; }

    /* ── My interests chips ── */
    .interest-chips { display: flex; flex-wrap: wrap; gap: 0.5rem; }
    .interest-chip-remove { display: inline-flex; align-items: center; gap: 0.32rem; padding: 0.38rem 0.5rem 0.38rem 0.78rem; border-radius: 999px; background: rgba(255,255,255,0.18); color: #fff; font-size: 0.78rem; }
    .chip-rm-btn { display: inline-flex; align-items: center; justify-content: center; width: 18px; height: 18px; border-radius: 50%; background: rgba(255,255,255,0.18); border: none; color: rgba(255,255,255,0.8); cursor: pointer; font-size: 0.68rem; padding: 0; line-height: 1; transition: background 0.15s; flex-shrink: 0; }
    .chip-rm-btn:hover { background: rgba(231,76,60,0.65); color: #fff; }

    /* ── Search bar ── */
    .search-row { display: flex; gap: 0.6rem; margin-bottom: 1.2rem; }
    .search-row input { flex: 1; padding: 0.8rem 1rem; border-radius: 14px; border: 1px solid rgba(255,255,255,0.2); background: rgba(255,255,255,0.12); color: #fff; outline: none; font-family: 'Poppins', sans-serif; font-size: 0.86rem; }
    .search-row input::placeholder { color: rgba(255,255,255,0.45); }
    .btn-reset-search { border: none; border-radius: 999px; padding: 0.75rem 1.2rem; background: rgba(255,255,255,0.14); color: #fff; font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 0.82rem; cursor: pointer; white-space: nowrap; }
    .btn-reset-search:hover { background: rgba(255,255,255,0.22); }

    /* ── Collapsible category blocks ── */
    .cat-block { border-radius: 16px; background: rgba(255,255,255,0.06); overflow: hidden; margin-bottom: 0.7rem; }
    .cat-block:last-child { margin-bottom: 0; }
    .cat-toggle { width: 100%; display: flex; align-items: center; justify-content: space-between; padding: 0.85rem 1rem; background: none; border: none; color: #fff; font-family: 'Poppins', sans-serif; font-size: 0.88rem; font-weight: 600; cursor: pointer; gap: 0.5rem; }
    .cat-toggle-left { display: flex; align-items: center; gap: 0.55rem; }
    .cat-count { font-size: 0.72rem; font-weight: 600; background: rgba(255,255,255,0.12); color: rgba(255,255,255,0.7); padding: 0.15rem 0.5rem; border-radius: 999px; }
    .cat-chevron { width: 16px; height: 16px; fill: currentColor; transition: transform 0.25s; flex-shrink: 0; }
    .cat-block.open > .cat-toggle .cat-chevron { transform: rotate(180deg); }
    .cat-body { display: none; padding: 0 0.9rem 0.9rem; }
    .cat-block.open > .cat-body { display: block; }

    /* ── Interest items ── */
    .interest-items { display: grid; gap: 0.5rem; }
    .interest-item { display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; padding: 0.7rem 0.85rem; border-radius: 12px; background: rgba(255,255,255,0.06); transition: background 0.15s; }
    .interest-item.item-hidden { display: none; }
    .interest-item-name { color: #fff; font-size: 0.84rem; font-weight: 500; }
    .interest-item-name.is-mine { color: #2ecc71; }
    .toggle-btn { border: none; border-radius: 999px; padding: 0.38rem 0.85rem; font-family: 'Poppins', sans-serif; font-size: 0.75rem; font-weight: 700; cursor: pointer; white-space: nowrap; }
    .toggle-btn.btn-remove { background: rgba(231,76,60,0.16); color: #e74c3c; border: 1px solid rgba(231,76,60,0.32); }
    .toggle-btn.btn-add    { background: rgba(255,255,255,0.12); color: rgba(255,255,255,0.85); border: 1px solid rgba(255,255,255,0.18); }
    .toggle-btn.btn-remove:hover { background: rgba(231,76,60,0.28); }
    .toggle-btn.btn-add:hover    { background: rgba(255,255,255,0.2); }

    .no-results-msg { color: rgba(255,255,255,0.55); font-size: 0.86rem; padding: 0.5rem 0 0.25rem; display: none; }
</style>
@endsection

@section('content')
<div class="interests-page">

    {{-- ── My interests ── --}}
    <div class="card panel">
        <div class="panel-title">
            Mes intérêts
            <span class="count-badge">{{ $userInterets->count() }}</span>
        </div>

        @if ($userInterets->isNotEmpty())
            <div class="interest-chips" id="myChips">
                @foreach ($userInterets->sortBy(fn ($i) => $i->categorie . '|' . $i->nom) as $interet)
                    <div class="interest-chip-remove" data-interet-id="{{ $interet->id }}">
                        <span>{{ $interet->nom }}</span>
                        <form method="POST" action="{{ route('interets.toggle') }}" style="margin:0;display:contents;">
                            @csrf
                            <input type="hidden" name="interet_id" value="{{ $interet->id }}">
                            <button type="submit" class="chip-rm-btn" title="Retirer">✕</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <p class="empty-copy" id="emptyMsg">Aucun intérêt sélectionné pour le moment. Explore le catalogue ci-dessous pour en ajouter.</p>
        @endif
    </div>

    {{-- ── Catalogue ── --}}
    <div class="card panel">
        <div class="panel-title">Catalogue d'intérêts</div>

        <div class="search-row">
            <input type="text" id="catalogSearch" placeholder="Rechercher un intérêt…" autocomplete="off">
            <button type="button" class="btn-reset-search" id="resetSearch">Effacer</button>
        </div>
        <div class="no-results-msg" id="noResults">Aucun intérêt trouvé pour cette recherche.</div>

        @foreach ($interets as $categorie => $liste)
            <div class="cat-block" data-cat="{{ $loop->index }}">
                <button type="button" class="cat-toggle">
                    <span class="cat-toggle-left">
                        {{ $categorie }}
                        <span class="cat-count">{{ $liste->count() }}</span>
                    </span>
                    <svg class="cat-chevron" viewBox="0 0 24 24"><path d="M7 10l5 5 5-5z"/></svg>
                </button>
                <div class="cat-body">
                    <div class="interest-items">
                        @foreach ($liste as $interet)
                            @php $mine = in_array($interet->id, $userInteretIds, true); @endphp
                            <div class="interest-item" data-name="{{ mb_strtolower($interet->nom) }}" data-id="{{ $interet->id }}">
                                <span class="interest-item-name {{ $mine ? 'is-mine' : '' }}">{{ $interet->nom }}</span>
                                <form method="POST" action="{{ route('interets.toggle') }}" style="margin:0;">
                                    @csrf
                                    <input type="hidden" name="interet_id" value="{{ $interet->id }}">
                                    <button type="submit" class="toggle-btn {{ $mine ? 'btn-remove' : 'btn-add' }}">
                                        {{ $mine ? '− Retirer' : '+ Ajouter' }}
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

@section('scripts')
<script>
    // ── Collapsible categories ──
    document.querySelectorAll('.cat-toggle').forEach(btn => {
        btn.addEventListener('click', function () {
            this.closest('.cat-block').classList.toggle('open');
        });
    });

    // ── Client-side search ──
    const searchInput  = document.getElementById('catalogSearch');
    const resetBtn     = document.getElementById('resetSearch');
    const noResults    = document.getElementById('noResults');

    function filterCatalog() {
        const q = searchInput.value.trim().toLowerCase();
        let totalVisible = 0;

        document.querySelectorAll('.cat-block').forEach(cat => {
            const items = cat.querySelectorAll('.interest-item');
            let catVisible = 0;

            items.forEach(item => {
                const name  = item.dataset.name || '';
                const match = q === '' || name.includes(q);
                item.classList.toggle('item-hidden', !match);
                if (match) catVisible++;
            });

            // Auto-expand matching categories while searching
            if (q !== '') {
                cat.classList.toggle('open', catVisible > 0);
            }
            cat.style.display = catVisible === 0 && q !== '' ? 'none' : '';
            totalVisible += catVisible;
        });

        noResults.style.display = (totalVisible === 0 && q !== '') ? 'block' : 'none';
    }

    searchInput.addEventListener('input', filterCatalog);

    resetBtn.addEventListener('click', () => {
        searchInput.value = '';
        filterCatalog();
        document.querySelectorAll('.cat-block').forEach(c => c.classList.remove('open'));
        searchInput.focus();
    });
</script>
@endsection
