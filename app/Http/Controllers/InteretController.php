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

        $userInterets   = $user->interets()->orderBy('categorie')->orderBy('nom')->get();
        $userInteretIds = $userInterets->pluck('id')->all();

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

        return view('interests.index', [
            'interets'          => $interets,
            'userInterets'      => $userInterets,
            'categories'        => $categories,
            'userInteretIds'    => $userInteretIds,
        ]);
    }

    public function toggle(Request $request)
    {
        $validated = $request->validate([
            'interet_id' => 'required|exists:interets,id',
        ]);

        $user      = Auth::user();
        $interetId = (int) $validated['interet_id'];

        if ($user->interets()->where('interets.id', $interetId)->exists()) {
            $user->interets()->detach($interetId);
            $status  = 'removed';
            $message = 'Intérêt retiré de ton profil.';
        } else {
            $user->interets()->syncWithoutDetaching([$interetId]);
            $status  = 'added';
            $message = 'Intérêt ajouté à ton profil.';
        }

        if ($request->expectsJson()) {
            return response()->json(['status' => $status]);
        }

        return back()->with('success', $message);
    }
}
