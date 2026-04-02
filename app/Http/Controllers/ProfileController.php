<?php

namespace App\Http\Controllers;

use App\Models\Interet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar_url) {
                Storage::disk('public')->delete($user->avatar_url);
            }

            $validated['avatar_url'] = $request->file('avatar')->store('avatars', 'public');
        }

        unset($validated['avatar']);

        $user->update($validated);

        return redirect()->route('profile.edit')->with('success', 'Profil mis a jour avec succes.');
    }
}
