<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AuthController extends Controller
{
    private const ALLOWED_EMAIL_DOMAINS = [
        'edu.cegeptr.qc.ca',
        'cegeptr.qc.ca',
    ];

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', function ($attribute, $value, $fail) {
                if (!$this->isAllowedEmailDomain($value)) {
                    $fail('L\'adresse courriel doit se terminer par @edu.cegeptr.qc.ca ou @cegeptr.qc.ca.');
                }
            }],
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if ($user->blacklisted) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Votre compte a été suspendu. Contactez l\'administration.',
                ])->withInput($request->only('email'));
            }

            $request->session()->regenerate();
            return redirect()->intended('/home');
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis sont incorrects.',
        ])->withInput($request->only('email'));
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => ['required', 'email', 'unique:users,email', function ($attribute, $value, $fail) {
                if (!$this->isAllowedEmailDomain($value)) {
                    $fail('L\'adresse courriel doit se terminer par @edu.cegeptr.qc.ca ou @cegeptr.qc.ca.');
                }
            }],
            'password' => ['required', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()],
            'position' => 'required|in:etudiant,personnel',
        ]);

        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => $request->password,
            'position' => $request->position,
        ]);

        Auth::login($user);

        return redirect('/home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', function ($attribute, $value, $fail) {
                if (!$this->isAllowedEmailDomain($value)) {
                    $fail('L\'adresse courriel doit se terminer par @edu.cegeptr.qc.ca ou @cegeptr.qc.ca.');
                }
            }],
        ]);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Un lien de réinitialisation a été envoyé à votre adresse courriel.');
        }

        return back()->withErrors(['email' => 'Impossible d\'envoyer le lien de réinitialisation.']);
    }

    private function isAllowedEmailDomain(string $email): bool
    {
        $domain = substr(strrchr($email, '@'), 1);

        return in_array(strtolower($domain), self::ALLOWED_EMAIL_DOMAINS);
    }
}
