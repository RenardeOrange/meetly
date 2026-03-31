@extends('layouts.app')

@section('title', 'Mon profil')

@section('styles')
<style>
    .profile-page {
        max-width: 520px;
        margin: 0 auto;
        animation: fadeInUp 0.5s ease-out;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .profile-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .profile-header .avatar-large {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.25);
        margin: 0 auto 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(10px);
        transition: transform 0.3s ease;
    }

    .profile-header .avatar-large:hover {
        transform: scale(1.05);
    }

    .profile-header .avatar-large svg {
        width: 50px;
        height: 50px;
        fill: rgba(255, 255, 255, 0.85);
    }

    .profile-header h1 {
        color: #fff;
        font-size: 1.3rem;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .profile-form .form-group {
        margin-bottom: 1rem;
    }

    .profile-form label {
        display: block;
        color: rgba(255, 255, 255, 0.85);
        font-size: 0.82rem;
        font-weight: 500;
        margin-bottom: 0.35rem;
        padding-left: 0.75rem;
    }

    .profile-form input,
    .profile-form textarea,
    .profile-form select {
        width: 100%;
        padding: 0.85rem 1.25rem;
        border: 2px solid rgba(255, 255, 255, 0.4);
        border-radius: 50px;
        background: linear-gradient(135deg, rgba(255, 200, 150, 0.35), rgba(255, 150, 150, 0.25));
        backdrop-filter: blur(10px);
        color: #c0392b;
        font-size: 0.92rem;
        font-family: 'Poppins', sans-serif;
        font-weight: 500;
        outline: none;
        transition: all 0.3s ease;
    }

    .profile-form textarea {
        border-radius: 16px;
        resize: vertical;
        min-height: 100px;
    }

    .profile-form select {
        appearance: none;
        cursor: pointer;
    }

    .profile-form select option {
        background: #fff;
        color: #333;
    }

    .profile-form input::placeholder,
    .profile-form textarea::placeholder {
        color: rgba(192, 57, 43, 0.5);
    }

    .profile-form input:focus,
    .profile-form textarea:focus,
    .profile-form select:focus {
        border-color: #fff;
        background: linear-gradient(135deg, rgba(255, 200, 150, 0.5), rgba(255, 150, 150, 0.4));
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.25);
    }

    .profile-form .email-display {
        padding: 0.85rem 1.25rem;
        border-radius: 50px;
        background: rgba(255, 255, 255, 0.08);
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.88rem;
        border: 2px solid rgba(255, 255, 255, 0.15);
    }

    .form-row {
        display: flex;
        gap: 0.75rem;
    }

    .form-row .form-group {
        flex: 1;
    }

    .btn-save {
        width: 100%;
        padding: 0.9rem;
        border: none;
        border-radius: 50px;
        background: #2c3e50;
        color: #fff;
        font-size: 1.05rem;
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        cursor: pointer;
        margin-top: 0.5rem;
        transition: all 0.3s ease;
    }

    .btn-save:hover {
        background: #34495e;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(44, 62, 80, 0.4);
    }

    .btn-save:active {
        transform: translateY(0);
    }

    .error-message {
        background: rgba(255, 255, 255, 0.9);
        color: #c0392b;
        padding: 0.75rem 1rem;
        border-radius: 12px;
        font-size: 0.85rem;
        margin-bottom: 1rem;
        text-align: center;
    }

    .char-count {
        text-align: right;
        font-size: 0.72rem;
        color: rgba(255, 255, 255, 0.5);
        margin-top: 0.25rem;
        padding-right: 0.75rem;
    }
</style>
@endsection

@section('content')
<div class="profile-page">
    <div class="profile-header">
        <div class="avatar-large">
            <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
        </div>
        <h1>Mon profil</h1>
    </div>

    @if (session('success'))
        <div class="success-toast">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="error-message">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="card">
        <form method="POST" action="{{ route('profile.update') }}" class="profile-form">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-group">
                    <label>Pr&eacute;nom</label>
                    <input type="text" name="prenom" value="{{ old('prenom', $user->prenom) }}" required>
                </div>
                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" name="nom" value="{{ old('nom', $user->nom) }}" required>
                </div>
            </div>

            <div class="form-group">
                <label>Courriel</label>
                <div class="email-display">{{ $user->email }}</div>
            </div>

            <div class="form-group">
                <label>Bio</label>
                <textarea name="bio" id="bioField" placeholder="Parlez-nous de vous..." maxlength="1000">{{ old('bio', $user->bio) }}</textarea>
                <div class="char-count"><span id="bioCount">{{ strlen(old('bio', $user->bio ?? '')) }}</span>/1000</div>
            </div>

            <div class="form-group">
                <label>Visibilit&eacute; du profil</label>
                <select name="visibilite">
                    <option value="public" {{ old('visibilite', $user->visibilite) === 'public' ? 'selected' : '' }}>Public</option>
                    <option value="prive" {{ old('visibilite', $user->visibilite) === 'prive' ? 'selected' : '' }}>Priv&eacute;</option>
                </select>
            </div>

            <button type="submit" class="btn-save">Enregistrer</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const bioField = document.getElementById('bioField');
    const bioCount = document.getElementById('bioCount');
    bioField?.addEventListener('input', function () {
        bioCount.textContent = this.value.length;
    });
</script>
@endsection
