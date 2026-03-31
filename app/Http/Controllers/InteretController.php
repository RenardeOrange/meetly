<?php

namespace App\Http\Controllers;

use App\Models\Interet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InteretController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $userInteretIds = $user->interets()->pluck('interets.id')->all();
        $search = trim((string) $request->get('search', ''));
        $selectedCategorie = $request->get('categorie', '');

        $query = Interet::query();

        if ($search !== '') {
            $query->where('nom', 'like', "%{$search}%");
        }

        if ($selectedCategorie !== '') {
            $query->where('categorie', $selectedCategorie);
        }

        $interets = $query
            ->orderBy('categorie')
            ->orderBy('nom')
            ->get()
            ->groupBy('categorie');

        $categories = Interet::query()
            ->select('categorie')
            ->distinct()
            ->orderBy('categorie')
            ->pluck('categorie');

        return view('interests.index', [
            'interets' => $interets,
            'categories' => $categories,
            'userInteretIds' => $userInteretIds,
            'search' => $search,
            'selectedCategorie' => $selectedCategorie,
        ]);
    }

    public function toggle(Request $request)
    {
        $validated = $request->validate([
            'interet_id' => 'required|exists:interets,id',
        ]);

        $user = Auth::user();
        $interetId = (int) $validated['interet_id'];

        if ($user->interets()->where('interets.id', $interetId)->exists()) {
            $user->interets()->detach($interetId);
            $status = 'removed';
            $message = 'Interet retire de votre profil.';
        } else {
            $user->interets()->syncWithoutDetaching([$interetId]);
            $status = 'added';
            $message = 'Interet ajoute a votre profil.';
        }

        if ($request->expectsJson()) {
            return response()->json(['status' => $status]);
        }

        return back()->with('success', $message);
    }
}
