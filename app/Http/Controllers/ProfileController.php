<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'visibilite' => 'required|in:public,prive',
        ]);

        $user->update($request->only('nom', 'prenom', 'bio', 'visibilite'));

        return redirect()->route('profile.edit')->with('success', 'Profil mis à jour avec succès.');
    }
}
