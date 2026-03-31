<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meetly - Cr&eacute;er un compte</title>
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
            padding: 2rem 0;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .register-container {
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
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.25);
            border-radius: 50%;
            margin: 0 auto 1.25rem;
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
            width: 50px;
            height: 50px;
            fill: rgba(255, 255, 255, 0.85);
        }

        .register-title {
            text-align: center;
            color: #fff;
            font-size: 1.4rem;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 1.75rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .input-group {
            position: relative;
            margin-bottom: 1rem;
        }

        .input-group input, .input-group select {
            width: 100%;
            padding: 0.9rem 1rem 0.9rem 3.25rem;
            border: 2px solid rgba(255, 255, 255, 0.5);
            border-radius: 50px;
            background: linear-gradient(135deg, rgba(255, 200, 150, 0.4), rgba(255, 150, 150, 0.3));
            backdrop-filter: blur(10px);
            color: #c0392b;
            font-size: 0.95rem;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            outline: none;
            transition: all 0.3s ease;
        }

        .input-group select {
            appearance: none;
            cursor: pointer;
        }

        .input-group input::placeholder, .input-group select option[value=""] {
            color: rgba(192, 57, 43, 0.6);
            font-weight: 400;
        }

        .input-group select option {
            background: #fff;
            color: #333;
        }

        .input-group input:focus, .input-group select:focus {
            border-color: #fff;
            background: linear-gradient(135deg, rgba(255, 200, 150, 0.6), rgba(255, 150, 150, 0.5));
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
            transform: scale(1.02);
        }

        .input-group .input-icon {
            position: absolute;
            left: 1.15rem;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            fill: #c0392b;
            transition: fill 0.3s ease;
            pointer-events: none;
            z-index: 1;
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

        .name-row {
            display: flex;
            gap: 0.75rem;
        }

        .name-row .input-group {
            flex: 1;
        }

        .btn-register-submit {
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
            position: relative;
            overflow: hidden;
        }

        .btn-register-submit::before {
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

        .btn-register-submit:hover {
            background: #34495e;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(44, 62, 80, 0.4);
        }

        .btn-register-submit:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-register-submit:active {
            transform: translateY(0);
        }

        .login-section {
            text-align: center;
            margin-top: 2rem;
        }

        .login-section p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            margin-bottom: 0.75rem;
        }

        .btn-back-login {
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

        .btn-back-login:hover {
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
            font-size: 0.72rem;
            margin-top: -0.4rem;
            margin-bottom: 0.75rem;
        }

        .input-group.error input, .input-group.error select {
            border-color: #fff;
            background: rgba(255, 100, 100, 0.3);
        }

        .field-error {
            color: #fff;
            font-size: 0.75rem;
            margin-top: 0.25rem;
            padding-left: 1.5rem;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .password-strength {
            margin-top: 0.35rem;
            padding-left: 1.5rem;
        }

        .strength-bar {
            height: 4px;
            border-radius: 2px;
            background: rgba(255, 255, 255, 0.2);
            overflow: hidden;
            margin-bottom: 0.25rem;
        }

        .strength-bar-fill {
            height: 100%;
            border-radius: 2px;
            transition: width 0.3s ease, background 0.3s ease;
            width: 0;
        }

        .strength-text {
            font-size: 0.72rem;
            color: rgba(255, 255, 255, 0.8);
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

        .btn-register-submit.loading .btn-text { display: none; }
        .btn-register-submit.loading .loading-spinner { display: inline-block; }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="avatar-circle">
            <svg viewBox="0 0 24 24">
                <path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
        </div>

        <h1 class="register-title">Cr&eacute;er un compte</h1>

        @if ($errors->any())
            <div class="error-message">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" id="registerForm" novalidate>
            @csrf

            <div class="name-row">
                <div class="input-group" id="prenomGroup">
                    <svg class="input-icon" viewBox="0 0 24 24">
                        <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                    </svg>
                    <input type="text" name="prenom" id="prenom" placeholder="Pr&eacute;nom" value="{{ old('prenom') }}" required>
                    <div class="field-error" id="prenomError" style="display:none;"></div>
                </div>
                <div class="input-group" id="nomGroup">
                    <svg class="input-icon" viewBox="0 0 24 24">
                        <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                    </svg>
                    <input type="text" name="nom" id="nom" placeholder="Nom" value="{{ old('nom') }}" required>
                    <div class="field-error" id="nomError" style="display:none;"></div>
                </div>
            </div>

            <div class="input-group" id="emailGroup">
                <svg class="input-icon" viewBox="0 0 24 24">
                    <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                </svg>
                <input type="email" name="email" id="email" placeholder="Courriel" value="{{ old('email') }}" required autocomplete="email">
                <div class="field-error" id="emailError" style="display:none;"></div>
            </div>
            <div class="email-hint">@edu.cegeptr.qc.ca ou @cegeptr.qc.ca uniquement</div>

            <div class="input-group" id="positionGroup">
                <svg class="input-icon" viewBox="0 0 24 24">
                    <path d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm6.82 6L12 12.72 5.18 9 12 5.28 18.82 9zM17 15.99l-5 2.73-5-2.73v-3.72L12 15l5-2.73v3.72z"/>
                </svg>
                <select name="position" id="position" required>
                    <option value="" disabled {{ old('position') ? '' : 'selected' }} style="color: rgba(192,57,43,0.6);">Statut</option>
                    <option value="etudiant" {{ old('position') === 'etudiant' ? 'selected' : '' }}>&#201;tudiant(e)</option>
                    <option value="personnel" {{ old('position') === 'personnel' ? 'selected' : '' }}>Personnel</option>
                </select>
                <div class="field-error" id="positionError" style="display:none;"></div>
            </div>

            <div class="input-group" id="passwordGroup">
                <svg class="input-icon" viewBox="0 0 24 24">
                    <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1s3.1 1.39 3.1 3.1v2z"/>
                </svg>
                <input type="password" name="password" id="password" placeholder="Mot de passe" required autocomplete="new-password">
                <button type="button" class="toggle-password" onclick="togglePassword('password', 'eyeIcon1')" aria-label="Afficher le mot de passe">
                    <svg id="eyeIcon1" viewBox="0 0 24 24">
                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                    </svg>
                </button>
                <div class="field-error" id="passwordError" style="display:none;"></div>
            </div>

            <div class="password-strength" id="passwordStrength" style="display:none;">
                <div class="strength-bar">
                    <div class="strength-bar-fill" id="strengthBarFill"></div>
                </div>
                <div class="strength-text" id="strengthText"></div>
            </div>

            <div class="input-group" id="passwordConfirmGroup">
                <svg class="input-icon" viewBox="0 0 24 24">
                    <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1s3.1 1.39 3.1 3.1v2z"/>
                </svg>
                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirmer le mot de passe" required autocomplete="new-password">
                <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation', 'eyeIcon2')" aria-label="Afficher le mot de passe">
                    <svg id="eyeIcon2" viewBox="0 0 24 24">
                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                    </svg>
                </button>
                <div class="field-error" id="confirmError" style="display:none;"></div>
            </div>

            <button type="submit" class="btn-register-submit" id="submitBtn">
                <span class="btn-text">Cr&eacute;er mon compte</span>
                <div class="loading-spinner"></div>
            </button>
        </form>

        <div class="login-section">
            <p>D&eacute;j&agrave; membre?</p>
            <a href="{{ route('login') }}" class="btn-back-login">Se connecter</a>
        </div>
    </div>

    <script>
        const allowedDomains = ['edu.cegeptr.qc.ca', 'cegeptr.qc.ca'];
        const eyeOpen = '<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>';
        const eyeClosed = '<path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46A11.804 11.804 0 001 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z"/>';

        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = eyeClosed;
            } else {
                input.type = 'password';
                icon.innerHTML = eyeOpen;
            }
        }

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

        function checkPasswordStrength(password) {
            let score = 0;
            if (password.length >= 8) score++;
            if (password.length >= 12) score++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) score++;
            if (/\d/.test(password)) score++;
            if (/[^a-zA-Z0-9]/.test(password)) score++;

            const levels = [
                { label: 'Tr\u00e8s faible', color: '#e74c3c', width: '20%' },
                { label: 'Faible', color: '#e67e22', width: '40%' },
                { label: 'Moyen', color: '#f1c40f', width: '60%' },
                { label: 'Fort', color: '#2ecc71', width: '80%' },
                { label: 'Tr\u00e8s fort', color: '#27ae60', width: '100%' },
            ];

            return levels[Math.min(score, 4)];
        }

        function showFieldError(groupId, errorId, message) {
            document.getElementById(groupId).classList.add('error');
            const el = document.getElementById(errorId);
            el.textContent = message;
            el.style.display = 'block';
        }

        function clearFieldError(groupId, errorId) {
            document.getElementById(groupId).classList.remove('error');
            document.getElementById(errorId).style.display = 'none';
        }

        // Email validation on blur
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

        // Password strength indicator
        document.getElementById('password').addEventListener('input', function () {
            const strengthDiv = document.getElementById('passwordStrength');
            const bar = document.getElementById('strengthBarFill');
            const text = document.getElementById('strengthText');

            if (this.value.length === 0) {
                strengthDiv.style.display = 'none';
                return;
            }

            strengthDiv.style.display = 'block';
            const strength = checkPasswordStrength(this.value);
            bar.style.width = strength.width;
            bar.style.background = strength.color;
            text.textContent = strength.label;
            text.style.color = strength.color;

            // Also check confirmation match if already filled
            const confirm = document.getElementById('password_confirmation').value;
            if (confirm && confirm !== this.value) {
                showFieldError('passwordConfirmGroup', 'confirmError', 'Les mots de passe ne correspondent pas.');
            } else if (confirm) {
                clearFieldError('passwordConfirmGroup', 'confirmError');
            }
        });

        // Confirm password match on blur
        document.getElementById('password_confirmation').addEventListener('blur', function () {
            if (this.value && this.value !== document.getElementById('password').value) {
                showFieldError('passwordConfirmGroup', 'confirmError', 'Les mots de passe ne correspondent pas.');
            } else if (this.value) {
                clearFieldError('passwordConfirmGroup', 'confirmError');
            }
        });

        document.getElementById('password_confirmation').addEventListener('input', function () {
            if (document.getElementById('passwordConfirmGroup').classList.contains('error')) {
                if (this.value === document.getElementById('password').value) {
                    clearFieldError('passwordConfirmGroup', 'confirmError');
                }
            }
        });

        // Form submit validation
        document.getElementById('registerForm').addEventListener('submit', function (e) {
            let hasError = false;

            const prenom = document.getElementById('prenom').value.trim();
            if (!prenom) { showFieldError('prenomGroup', 'prenomError', 'Le pr\u00e9nom est requis.'); hasError = true; }
            else clearFieldError('prenomGroup', 'prenomError');

            const nom = document.getElementById('nom').value.trim();
            if (!nom) { showFieldError('nomGroup', 'nomError', 'Le nom est requis.'); hasError = true; }
            else clearFieldError('nomGroup', 'nomError');

            const emailErr = validateEmail(document.getElementById('email').value);
            if (emailErr) { showFieldError('emailGroup', 'emailError', emailErr); hasError = true; }
            else clearFieldError('emailGroup', 'emailError');

            const position = document.getElementById('position').value;
            if (!position) { showFieldError('positionGroup', 'positionError', 'Le statut est requis.'); hasError = true; }
            else clearFieldError('positionGroup', 'positionError');

            const password = document.getElementById('password').value;
            if (!password) {
                showFieldError('passwordGroup', 'passwordError', 'Le mot de passe est requis.'); hasError = true;
            } else if (password.length < 8) {
                showFieldError('passwordGroup', 'passwordError', 'Minimum 8 caract\u00e8res.'); hasError = true;
            } else if (!/[a-z]/.test(password) || !/[A-Z]/.test(password)) {
                showFieldError('passwordGroup', 'passwordError', 'Doit contenir des majuscules et minuscules.'); hasError = true;
            } else if (!/\d/.test(password)) {
                showFieldError('passwordGroup', 'passwordError', 'Doit contenir au moins un chiffre.'); hasError = true;
            } else {
                clearFieldError('passwordGroup', 'passwordError');
            }

            const confirm = document.getElementById('password_confirmation').value;
            if (!confirm) {
                showFieldError('passwordConfirmGroup', 'confirmError', 'Veuillez confirmer le mot de passe.'); hasError = true;
            } else if (confirm !== password) {
                showFieldError('passwordConfirmGroup', 'confirmError', 'Les mots de passe ne correspondent pas.'); hasError = true;
            } else {
                clearFieldError('passwordConfirmGroup', 'confirmError');
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
