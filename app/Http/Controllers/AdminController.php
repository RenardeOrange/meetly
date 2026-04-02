<?php

namespace App\Http\Controllers;

use App\Models\Interet;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'users' => User::count(),
            'interets' => Interet::count(),
            'blacklisted' => User::where('blacklisted', true)->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function users(Request $request)
    {
        $query = User::query();

        if ($search = $request->get('search')) {
            $query->where(function ($nested) use ($search) {
                $nested->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->with('interets')->orderByDesc('created_at')->get();

        return view('admin.users', compact('users'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'nom'         => 'required|string|max:255',
            'prenom'      => 'required|string|max:255',
            'role'        => 'required|in:admin,user',
            'position'    => 'required|in:etudiant,personnel',
            'genre'       => 'nullable|in:homme,femme,non-binaire,autre',
            'orientation' => 'nullable|in:heterosexuel,homosexuel,bisexuel,pansexuel,autre',
        ]);

        $user->update($request->only('nom', 'prenom', 'role', 'position', 'genre', 'orientation'));

        return redirect()->route('admin.users')->with('success', 'Utilisateur mis a jour.');
    }

    public function deleteUser(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Impossible de supprimer un administrateur.');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Utilisateur supprime.');
    }

    public function toggleBlacklist(User $user)
    {
        $user->update([
            'blacklisted' => !$user->blacklisted,
        ]);

        $status = $user->blacklisted ? 'blackliste' : 'debloque';

        return redirect()->route('admin.users')->with('success', "Utilisateur {$status}.");
    }

    public function interets()
    {
        $interets = Interet::query()
            ->orderBy('categorie')
            ->orderBy('nom')
            ->get()
            ->groupBy('categorie');

        $categories = Interet::query()
            ->select('categorie')
            ->distinct()
            ->orderBy('categorie')
            ->pluck('categorie');

        return view('admin.interets', compact('interets', 'categories'));
    }

    public function storeInteret(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:interets,nom',
            'categorie' => 'required|string|max:255',
        ]);

        Interet::create($request->only('nom', 'categorie'));

        return redirect()->route('admin.interets')->with('success', 'Interet ajoute.');
    }

    public function updateInteret(Request $request, Interet $interet)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:interets,nom,' . $interet->id,
            'categorie' => 'required|string|max:255',
        ]);

        $interet->update($request->only('nom', 'categorie'));

        return redirect()->route('admin.interets')->with('success', 'Interet mis a jour.');
    }

    public function deleteInteret(Interet $interet)
    {
        $interet->users()->detach();
        $interet->delete();

        return redirect()->route('admin.interets')->with('success', 'Interet supprime.');
    }
}
