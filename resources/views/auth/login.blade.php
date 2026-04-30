<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meetly - Connexion</title>
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

        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 2rem;
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .avatar-circle {
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.25);
            border-radius: 50%;
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .avatar-circle:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        }

        .avatar-circle svg {
            width: 60px;
            height: 60px;
            fill: rgba(255, 255, 255, 0.85);
        }

        .login-title {
            text-align: center;
            color: #fff;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 2rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .input-group {
            position: relative;
            margin-bottom: 1.25rem;
        }

        .input-group input {
            width: 100%;
            padding: 1rem 1rem 1rem 3.5rem;
            border: 2px solid rgba(255, 255, 255, 0.5);
            border-radius: 50px;
            background: linear-gradient(135deg, rgba(255, 200, 150, 0.4), rgba(255, 150, 150, 0.3));
            backdrop-filter: blur(10px);
            color: #c0392b;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            outline: none;
            transition: all 0.3s ease;
        }

        .input-group input::placeholder {
            color: rgba(192, 57, 43, 0.6);
            font-weight: 400;
        }

        .input-group input:focus {
            border-color: #fff;
            background: linear-gradient(135deg, rgba(255, 200, 150, 0.6), rgba(255, 150, 150, 0.5));
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
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
            transition: fill 0.3s ease;
        }

        .input-group input:focus ~ .input-icon,
        .input-group input:focus + .input-icon {
            fill: #e74c3c;
        }

        .input-group .toggle-password {
            position: absolute;
            right: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.25rem;
        }

        .input-group .toggle-password svg {
            width: 20px;
            height: 20px;
            fill: #c0392b;
            transition: fill 0.3s ease;
        }

        .input-group .toggle-password:hover svg {
            fill: #e74c3c;
        }

        .btn-login {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 50px;
            background: #2c3e50;
            color: #fff;
            font-size: 1.1rem;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            cursor: pointer;
            margin-top: 0.5rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s ease, height 0.6s ease;
        }

        .btn-login:hover {
            background: #34495e;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(44, 62, 80, 0.4);
        }

        .btn-login:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .options-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
            margin-bottom: 1.5rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #fff;
            font-size: 0.85rem;
            cursor: pointer;
        }

        .remember-me input[type="checkbox"] {
            appearance: none;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.7);
            border-radius: 4px;
            cursor: pointer;
            position: relative;
            transition: all 0.2s ease;
        }

        .remember-me input[type="checkbox"]:checked {
            background: #fff;
            border-color: #fff;
        }

        .remember-me input[type="checkbox"]:checked::after {
            content: '\2713';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #e74c3c;
            font-size: 14px;
            font-weight: bold;
        }

        .forgot-link {
            color: #fff;
            font-size: 0.85rem;
            text-decoration: underline;
            transition: opacity 0.3s ease;
        }

        .forgot-link:hover {
            opacity: 0.8;
        }

        .register-section {
            text-align: center;
            margin-top: 2.5rem;
        }

        .register-section p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            margin-bottom: 0.75rem;
        }

        .btn-register {
            display: inline-block;
            padding: 0.6rem 2rem;
            border: 2px solid rgba(255, 255, 255, 0.7);
            border-radius: 50px;
            color: #fff;
            font-size: 0.9rem;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-register:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: #fff;
            transform: translateY(-2px);
        }

        .error-message {
            background: rgba(255, 255, 255, 0.9);
            color: #c0392b;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            font-size: 0.85rem;
            margin-bottom: 1rem;
            animation: shakeError 0.4s ease;
            text-align: center;
        }
        .success-message {
            background: rgba(46, 204, 113, 0.2);
            border: 2px solid rgba(46, 204, 113, 0.45);
            color: #fff;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            font-size: 0.85rem;
            margin-bottom: 1rem;
            text-align: center;
        }

        @keyframes shakeError {
            0%, 100% { transform: translateX(0); }
            20% { transform: translateX(-8px); }
            40% { transform: translateX(8px); }
            60% { transform: translateX(-5px); }
            80% { transform: translateX(5px); }
        }

        .email-hint {
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.75rem;
            margin-top: -0.5rem;
            margin-bottom: 1rem;
        }

        .input-group.error input {
            border-color: #fff;
            background: rgba(255, 100, 100, 0.3);
        }

        .field-error {
            color: #fff;
            font-size: 0.78rem;
            margin-top: 0.3rem;
            padding-left: 1.5rem;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .btn-login.loading .btn-text { display: none; }
        .btn-login.loading .loading-spinner { display: inline-block; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="avatar-circle">
            <svg viewBox="0 0 24 24">
                <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
            </svg>
        </div>

        <h1 class="login-title">Connexion membre</h1>

        @if ($errors->any())
            <div class="error-message">
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('status'))
            <div class="success-message">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
            @csrf

            <div class="input-group" id="emailGroup">
                <svg class="input-icon" viewBox="0 0 24 24">
                    <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                </svg>
                <input
                    type="email"
                    name="email"
                    id="email"
                    placeholder="Courriel"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                >
                <div class="field-error" id="emailError" style="display:none;"></div>
            </div>

            <div class="email-hint">@edu.cegeptr.qc.ca ou @cegeptr.qc.ca uniquement</div>

            <div class="input-group" id="passwordGroup">
                <svg class="input-icon" viewBox="0 0 24 24">
                    <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1s3.1 1.39 3.1 3.1v2z"/>
                </svg>
                <input
                    type="password"
                    name="password"
                    id="password"
                    placeholder="Mot de passe"
                    required
                    autocomplete="current-password"
                >
                <button type="button" class="toggle-password" onclick="togglePassword()" aria-label="Afficher le mot de passe">
                    <svg id="eyeIcon" viewBox="0 0 24 24">
                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                    </svg>
                </button>
                <div class="field-error" id="passwordError" style="display:none;"></div>
            </div>

            <button type="submit" class="btn-login" id="submitBtn">
                <span class="btn-text">Se connecter</span>
                <div class="loading-spinner"></div>
            </button>

            <div class="options-row">
                <label class="remember-me">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    Se souvenir de moi
                </label>
                <a href="{{ route('password.request') }}" class="forgot-link">Mot de passe oubli&eacute;?</a>
            </div>
        </form>

        <div class="register-section">
            <p>Pas encore membre?</p>
            <a href="{{ route('register') }}" class="btn-register">Cr&eacute;er un compte</a>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = '<path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46A11.804 11.804 0 001 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z"/>';
            } else {
                input.type = 'password';
                icon.innerHTML = '<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>';
            }
        }

        const allowedDomains = ['edu.cegeptr.qc.ca', 'cegeptr.qc.ca'];

        function validateEmail(email) {
            if (!email) return 'Le courriel est requis.';
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) return 'Format de courriel invalide.';
            const domain = email.split('@')[1]?.toLowerCase();
            if (!allowedDomains.includes(domain)) {
                return 'Le courriel doit se terminer par @edu.cegeptr.qc.ca ou @cegeptr.qc.ca.';
            }
            return null;
        }

        function showFieldError(groupId, errorId, message) {
            const group = document.getElementById(groupId);
            const error = document.getElementById(errorId);
            group.classList.add('error');
            error.textContent = message;
            error.style.display = 'block';
        }

        function clearFieldError(groupId, errorId) {
            const group = document.getElementById(groupId);
            const error = document.getElementById(errorId);
            group.classList.remove('error');
            error.style.display = 'none';
        }

        document.getElementById('email').addEventListener('blur', function () {
            const err = validateEmail(this.value);
            if (err) showFieldError('emailGroup', 'emailError', err);
            else clearFieldError('emailGroup', 'emailError');
        });

        document.getElementById('email').addEventListener('input', function () {
            if (document.getElementById('emailGroup').classList.contains('error')) {
                const err = validateEmail(this.value);
                if (!err) clearFieldError('emailGroup', 'emailError');
            }
        });

        document.getElementById('loginForm').addEventListener('submit', function (e) {
            let hasError = false;

            const emailErr = validateEmail(document.getElementById('email').value);
            if (emailErr) {
                showFieldError('emailGroup', 'emailError', emailErr);
                hasError = true;
            } else {
                clearFieldError('emailGroup', 'emailError');
            }

            const password = document.getElementById('password').value;
            if (!password) {
                showFieldError('passwordGroup', 'passwordError', 'Le mot de passe est requis.');
                hasError = true;
            } else {
                clearFieldError('passwordGroup', 'passwordError');
            }

            if (hasError) {
                e.preventDefault();
                return;
            }

            const btn = document.getElementById('submitBtn');
            btn.classList.add('loading');
            btn.disabled = true;
        });
    </script>
</body>
</html>
