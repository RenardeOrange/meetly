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
        $userInteretIds = $user->interets()->pluck('interets.id')->toArray();

        $query = Interet::query();

        if ($search = $request->get('search')) {
            $query->where('nom', 'like', "%{$search}%");
        }

        if ($categorie = $request->get('categorie')) {
            $query->where('categorie', $categorie);
        }

        $interets = $query->orderBy('categorie')->orderBy('nom')->get()->groupBy('categorie');
        $categories = Interet::distinct()->pluck('categorie')->sort()->values();

        return view('interests.index', compact('interets', 'categories', 'userInteretIds'));
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'interet_id' => 'required|exists:interets,id',
        ]);

        $user = Auth::user();
        $interetId = $request->interet_id;

        if ($user->interets()->where('interets.id', $interetId)->exists()) {
            $user->interets()->detach($interetId);
            return response()->json(['status' => 'removed']);
        }

        $user->interets()->attach($interetId);
        return response()->json(['status' => 'added']);
    }
}
