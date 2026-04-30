@extends('layouts.app')

@section('title', __('app.group_create'))

@section('styles')
<style>
    .create-page { max-width: 560px; margin: 0 auto; }
    .create-title { color: #fff; font-size: 1.2rem; font-weight: 700; margin-bottom: 1.5rem; }
    .form-card { background: rgba(255,255,255,0.12); backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.2); border-radius: 20px; padding: 2rem; display: flex; flex-direction: column; gap: 1.25rem; }
    .field label { display: block; color: rgba(255,255,255,0.85); font-size: 0.82rem; font-weight: 600; margin-bottom: 0.4rem; }
    .field input[type="text"], .field textarea { width: 100%; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.22); border-radius: 12px; color: #fff; font-family: 'Poppins', sans-serif; font-size: 0.88rem; padding: 0.75rem 1rem; outline: none; box-sizing: border-box; }
    .field input::placeholder, .field textarea::placeholder { color: rgba(255,255,255,0.4); }
    .field textarea { resize: vertical; min-height: 80px; }
    .field input[type="file"] { color: rgba(255,255,255,0.7); font-size: 0.82rem; }
    .toggle-row { display: flex; align-items: center; justify-content: space-between; }
    .toggle-row span { color: rgba(255,255,255,0.85); font-size: 0.88rem; }
    .toggle-row small { color: rgba(255,255,255,0.5); font-size: 0.75rem; }
    .toggle { position: relative; width: 48px; height: 26px; }
    .toggle input { opacity: 0; width: 0; height: 0; }
    .toggle-slider { position: absolute; inset: 0; background: rgba(255,255,255,0.2); border-radius: 999px; cursor: pointer; transition: background 0.25s; }
    .toggle-slider::before { content: ''; position: absolute; width: 20px; height: 20px; left: 3px; bottom: 3px; background: #fff; border-radius: 50%; transition: transform 0.25s; }
    .toggle input:checked + .toggle-slider { background: #2ecc71; }
    .toggle input:checked + .toggle-slider::before { transform: translateX(22px); }
    .interest-grid { display: flex; flex-wrap: wrap; gap: 0.4rem; max-height: 180px; overflow-y: auto; padding: 0.5rem 0; }
    .interest-check { position: relative; }
    .interest-check input { position: absolute; opacity: 0; }
    .interest-check span { display: inline-flex; align-items: center; padding: 0.3rem 0.7rem; border-radius: 999px; background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.8); font-size: 0.75rem; cursor: pointer; transition: all 0.2s; }
    .interest-check input:checked + span { background: #fff; color: #c0392b; font-weight: 700; }
    .btn-submit { background: #fff; color: #c0392b; border: none; border-radius: 999px; padding: 0.9rem; font-family: 'Poppins', sans-serif; font-size: 0.9rem; font-weight: 700; cursor: pointer; width: 100%; }
    .field-error { color: #e74c3c; font-size: 0.75rem; margin-top: 0.25rem; }
    .categ-label { color: rgba(255,255,255,0.5); font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; width: 100%; margin-top: 0.4rem; }
</style>
@endsection

@section('content')
<div class="create-page">
    <div class="create-title">{{ __('app.group_create') }}</div>

    <form class="form-card" method="POST" action="{{ route('groups.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="field">
            <label>{{ __('app.group_name_label') }}</label>
            <input type="text" name="nom" value="{{ old('nom') }}" placeholder="{{ __('app.group_name_example') }}" maxlength="60" required>
            @error('nom')<div class="field-error">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label>{{ __('app.group_desc_label') }}</label>
            <textarea name="description" placeholder="{{ __('app.group_what_about') }}" maxlength="500">{{ old('description') }}</textarea>
        </div>

        <div class="field">
            <label>{{ __('app.group_photo') }}</label>
            <input type="file" name="avatar" accept="image/*">
        </div>

        <div class="field">
            <label>{{ __('app.group_interests') }}</label>
            <div class="interest-grid">
                @foreach($interets as $categorie => $liste)
                    <span class="categ-label">{{ $categorie }}</span>
                    @foreach($liste as $interet)
                        <label class="interest-check">
                            <input type="checkbox" name="interets[]" value="{{ $interet->id }}" {{ in_array($interet->id, old('interets', []), true) ? 'checked' : '' }}>
                            <span>{{ $interet->nom }}</span>
                        </label>
                    @endforeach
                @endforeach
            </div>
        </div>

        <div class="field">
            <div class="toggle-row">
                <div>
                    <span>{{ __('app.group_public_toggle') }}</span><br>
                    <small>{{ __('app.group_public_desc') }}</small>
                </div>
                <label class="toggle">
                    <input type="checkbox" name="est_public" value="1" {{ old('est_public') ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
            </div>
        </div>

        <button type="submit" class="btn-submit">{{ __('app.create_group_btn') }}</button>
    </form>
</div>
@endsection
