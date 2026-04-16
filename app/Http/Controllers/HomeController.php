<?php

namespace App\Http\Controllers;

use App\Models\Interet;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $selectedInterets = collect($request->input('interets', []))
            ->filter(fn ($id) => filled($id))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        // Ensure current user's interests are loaded for matchScore computation
        $user->loadMissing('interets');

        $usersToSwipe = User::query()
            ->with('interets')
            ->where('id', '!=', $user->id)
            ->where('blacklisted', false)
            ->where('visibilite', 'public')
            ->when($request->filled('search'), function (Builder $query) use ($request) {
                $search = trim((string) $request->string('search'));
                $query->where(function (Builder $nested) use ($search) {
                    $nested->where('prenom', 'like', "%{$search}%")
                        ->orWhere('nom', 'like', "%{$search}%")
                        ->orWhere('numero_programme', 'like', "%{$search}%")
                        ->orWhere('bio', 'like', "%{$search}%");
                });
            })
            ->when($selectedInterets->isNotEmpty(), function (Builder $query) use ($selectedInterets) {
                foreach ($selectedInterets as $interetId) {
                    $query->whereHas('interets', function (Builder $interetsQuery) use ($interetId) {
                        $interetsQuery->where('interets.id', $interetId);
                    });
                }
            })
            ->whereDoesntHave('matchesAsUser1', function (Builder $query) use ($user) {
                $query->where('user_2_id', $user->id);
            })
            ->whereDoesntHave('matchesAsUser2', function (Builder $query) use ($user) {
                $query->where('user_1_id', $user->id);
            })
            ->orderByDesc('id')
            ->limit(20)
            ->get();

        // Compute match scores and sort by descending score (profiles with shared interests first)
        $matchScores = $usersToSwipe->mapWithKeys(fn ($p) => [$p->id => $user->matchScore($p)]);
        $usersToSwipe = $usersToSwipe->sortByDesc(fn ($p) => $matchScores[$p->id])->values()->take(10);

        $interetsParCategorie = Interet::query()
            ->orderBy('categorie')
            ->orderBy('nom')
            ->get()
            ->groupBy('categorie');

        return view('home', [
            'user'                 => $user,
            'usersToSwipe'         => $usersToSwipe,
            'matchScores'          => $matchScores,
            'interetsParCategorie' => $interetsParCategorie,
            'selectedInterets'     => $selectedInterets->all(),
            'search'               => $request->string('search')->toString(),
        ]);
    }
}
