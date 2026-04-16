@extends('layouts.app')

@section('title', 'Admin intérêts')

@section('styles')
<style>
    .admin-interests { display: grid; grid-template-columns: 340px minmax(0, 1fr); gap: 1.5rem; }
    .panel { padding: 1.4rem; }
    .panel h1, .panel h2, .category-block h3 { color: #fff; }
    .panel p { color: rgba(255, 255, 255, 0.74); margin-bottom: 1rem; }
    .create-form, .edit-form { display: grid; gap: 0.75rem; }
    .create-form input, .edit-form input { width: 100%; padding: 0.8rem 0.9rem; border-radius: 14px; border: 1px solid rgba(255, 255, 255, 0.2); background: rgba(255, 255, 255, 0.12); color: #fff; }
    .create-form button, .edit-form button, .back-link, .delete-form button { border: none; border-radius: 999px; padding: 0.8rem 1rem; font-weight: 700; cursor: pointer; text-decoration: none; text-align: center; }
    .create-form button, .edit-form .save-btn { background: #fff; color: #c0392b; }
    .back-link, .delete-form button { background: rgba(255, 255, 255, 0.15); color: #fff; }
    .category-list { display: grid; gap: 1rem; }
    .category-block { padding: 1rem; border-radius: 18px; background: rgba(255, 255, 255, 0.08); }
    .interest-items { display: grid; gap: 0.75rem; margin-top: 0.85rem; }
    .interest-row { padding: 0.9rem; border-radius: 16px; background: rgba(255, 255, 255, 0.08); }
    .edit-form { grid-template-columns: 1fr 1fr auto; align-items: center; }
    .delete-form { margin-top: 0.6rem; }
    @media (max-width: 980px) { .admin-interests { grid-template-columns: 1fr; } }
    @media (max-width: 760px) { .edit-form { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<div class="admin-interests">
    <aside class="card panel">
        <h1>Ajouter un intérêt</h1>
        <p>CRUD admin sur les hobbies et la taxonomie.</p>
        <form method="POST" action="{{ route('admin.interets.store') }}" class="create-form">
            @csrf
            <input type="text" name="nom" placeholder="Nom de l'intérêt" required>
            <input type="text" name="categorie" placeholder="Catégorie" list="categories" required>
            <button type="submit">Ajouter</button>
        </form>
        <datalist id="categories">
            @foreach ($categories as $categorie)
                <option value="{{ $categorie }}"></option>
            @endforeach
        </datalist>
        <div style="margin-top:1rem;"><a href="{{ route('admin.dashboard') }}" class="back-link">Retour admin</a></div>
    </aside>

    <section class="card panel">
        <h2>Liste des intérêts</h2>
        <div class="category-list">
            @foreach ($interets as $categorie => $liste)
                <div class="category-block">
                    <h3>{{ $categorie }}</h3>
                    <div class="interest-items">
                        @foreach ($liste as $interet)
                            <div class="interest-row">
                                <form method="POST" action="{{ route('admin.interets.update', $interet) }}" class="edit-form">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="nom" value="{{ $interet->nom }}" required>
                                    <input type="text" name="categorie" value="{{ $interet->categorie }}" required>
                                    <button type="submit" class="save-btn">Sauver</button>
                                </form>
                                <form method="POST" action="{{ route('admin.interets.delete', $interet) }}" class="delete-form" onsubmit="return confirm('Supprimer cet intérêt ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Supprimer</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</div>
@endsection
