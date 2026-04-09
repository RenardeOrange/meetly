<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meetly - Nouveau mot de passe</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f97316, #ef4444, #ec4899);
            background-size: 200% 200%;
            animation: gradientShift 8s ease infinite;
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .reset-container {
            width: 100%;
            max-width: 420px;
            padding: 2rem;
            animation: fadeInUp 0.6s ease-out;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .icon-circle {
            width: 100px;
            height: 100px;
            background: rgba(255,255,255,0.25);
            border-radius: 50%;
            margin: 0 auto 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }
        .icon-circle svg { width: 50px; height: 50px; fill: rgba(255,255,255,0.85); }
        .reset-title {
            text-align: center;
            color: #fff;
            font-size: 1.3rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 0.75rem;
        }
        .reset-desc {
            text-align: center;
            color: rgba(255,255,255,0.8);
            font-size: 0.85rem;
            margin-bottom: 1.75rem;
            line-height: 1.5;
        }
        .input-group { position: relative; margin-bottom: 1rem; }
        .input-group input {
            width: 100%;
            padding: 1rem 1rem 1rem 3.5rem;
            border: 2px solid rgba(255,255,255,0.5);
            border-radius: 50px;
            background: linear-gradient(135deg, rgba(255,200,150,0.4), rgba(255,150,150,0.3));
            backdrop-filter: blur(10px);
            color: #c0392b;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            outline: none;
            transition: all 0.3s ease;
        }
        .input-group input::placeholder { color: rgba(192,57,43,0.6); font-weight: 400; }
        .input-group input:focus {
            border-color: #fff;
            background: linear-gradient(135deg, rgba(255,200,150,0.6), rgba(255,150,150,0.5));
            box-shadow: 0 0 20px rgba(255,255,255,0.3);
            transform: scale(1.02);
        }
        .input-group .input-icon {
            position: absolute;
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            width: 22px;
            height: 22px;
            fill: #c0392b;
        }
        .error-message {
            background: rgba(255,255,255,0.9);
            color: #c0392b;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            font-size: 0.85rem;
            margin-bottom: 1rem;
            text-align: center;
        }
        .btn-submit {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 50px;
            background: #2c3e50;
            color: #fff;
            font-size: 1.05rem;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-submit:hover {
            background: #34495e;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(44,62,80,0.4);
        }
        .back-section { text-align: center; margin-top: 2rem; }
        .btn-back {
            display: inline-block;
            padding: 0.6rem 2rem;
            border: 2px solid rgba(255,255,255,0.7);
            border-radius: 50px;
            color: #fff;
            font-size: 0.9rem;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .btn-back:hover { background: rgba(255,255,255,0.15); border-color: #fff; transform: translateY(-2px); }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="icon-circle">
            <svg viewBox="0 0 24 24"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1s3.1 1.39 3.1 3.1v2z"/></svg>
        </div>

        <h1 class="reset-title">Nouveau mot de passe</h1>
        <p class="reset-desc">Choisissez un nouveau mot de passe pour votre compte.</p>

        @if ($errors->any())
            <div class="error-message">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="input-group">
                <svg class="input-icon" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                <input type="email" name="email" placeholder="Courriel" value="{{ old('email') }}" required autocomplete="email">
            </div>

            <div class="input-group">
                <svg class="input-icon" viewBox="0 0 24 24"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1s3.1 1.39 3.1 3.1v2z"/></svg>
                <input type="password" name="password" placeholder="Nouveau mot de passe" required autocomplete="new-password">
            </div>

            <div class="input-group">
                <svg class="input-icon" viewBox="0 0 24 24"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1s3.1 1.39 3.1 3.1v2z"/></svg>
                <input type="password" name="password_confirmation" placeholder="Confirmer le mot de passe" required autocomplete="new-password">
            </div>

            <button type="submit" class="btn-submit">Réinitialiser le mot de passe</button>
        </form>

        <div class="back-section">
            <a href="{{ route('login') }}" class="btn-back">Retour à la connexion</a>
        </div>
    </div>
</body>
</html>
