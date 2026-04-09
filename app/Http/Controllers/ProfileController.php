<?php

namespace App\Http\Controllers;

use App\Models\Interet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $user->load('interets');

        $interetsParCategorie = Interet::query()
            ->orderBy('categorie')
            ->orderBy('nom')
            ->get()
            ->groupBy('categorie');

        return view('profile.edit', compact('user', 'interetsParCategorie'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nom'              => 'required|string|max:255',
            'prenom'           => 'required|string|max:255',
            'numero_programme' => 'nullable|string|max:255',
            'bio'              => 'nullable|string|max:200',
            'visibilite'       => 'required|in:public,prive',
            'avatar'           => 'nullable|image|max:2048',
            'type_connexion'   => 'nullable|array',
            'type_connexion.*' => 'in:amitie,activites,etudes,sorties,gaming',
            'dark_mode'        => 'nullable|boolean',
            'langue'           => 'nullable|in:fr,en',
            'current_password' => 'nullable|required_with:password,password_confirmation|current_password',
            'password'         => ['nullable', 'required_with:current_password,password_confirmation', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()],
            'password_confirmation' => 'nullable|required_with:current_password,password',
        ]);

        $validated['dark_mode'] = $request->boolean('dark_mode');
        $validated['langue']    = $request->input('langue', 'fr');

        if ($request->hasFile('avatar')) {
            if ($user->avatar_url) {
                Storage::disk('public')->delete($user->avatar_url);
            }

            $validated['avatar_url'] = $request->file('avatar')->store('avatars', 'public');
        }

        unset($validated['avatar']);
        unset($validated['current_password'], $validated['password_confirmation']);

        if (! empty($validated['password'])) {
            $user->setRememberToken(Str::random(60));
        } else {
            unset($validated['password']);
        }

        $user->update($validated);
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profil mis a jour avec succes.');
    }
}
