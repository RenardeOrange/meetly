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
            ->when($user->genre && $user->orientation, function (Builder $query) use ($user) {
                $this->applyCompatibilityFilter($query, $user);
            })
            ->whereDoesntHave('matchesAsUser1', function (Builder $query) use ($user) {
                $query->where('user_2_id', $user->id);
            })
            ->whereDoesntHave('matchesAsUser2', function (Builder $query) use ($user) {
                $query->where('user_1_id', $user->id);
            })
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        $interetsParCategorie = Interet::query()
            ->orderBy('categorie')
            ->orderBy('nom')
            ->get()
            ->groupBy('categorie');

        return view('home', [
            'user'              => $user,
            'usersToSwipe'      => $usersToSwipe,
            'interetsParCategorie' => $interetsParCategorie,
            'selectedInterets'  => $selectedInterets->all(),
            'search'            => $request->string('search')->toString(),
        ]);
    }

    private function applyCompatibilityFilter(Builder $query, User $user): void
    {
        // 1. Filter by what genres the current user seeks based on their orientation
        $soughtGenres = $this->soughtGenres($user);
        if (!empty($soughtGenres)) {
            // Include candidates who haven't set their genre yet too
            $query->where(function (Builder $q) use ($soughtGenres) {
                $q->whereNull('genre')->orWhereIn('genre', $soughtGenres);
            });
        }

        // 2. Filter candidates who are attracted to the current user's genre
        $userGenre = $user->genre;

        // Hetero candidate attracted to the opposite binary gender
        $heteroCompatibleGender = match($userGenre) {
            'homme' => 'femme',
            'femme' => 'homme',
            default => null,
        };

        $query->where(function (Builder $q) use ($userGenre, $heteroCompatibleGender) {
            // Candidate has not set their orientation → show anyway
            $q->whereNull('orientation');

            // Bi / pan / autre → attracted to all
            $q->orWhereIn('orientation', ['bisexuel', 'pansexuel', 'autre']);

            // Homo → attracted to same genre as user
            $q->orWhere(function (Builder $q2) use ($userGenre) {
                $q2->where('orientation', 'homosexuel')->where('genre', $userGenre);
            });

            // Hetero → attracted to opposite binary gender
            if ($heteroCompatibleGender) {
                $q->orWhere(function (Builder $q2) use ($heteroCompatibleGender) {
                    $q2->where('orientation', 'heterosexuel')->where('genre', $heteroCompatibleGender);
                });
            }
        });
    }

    private function soughtGenres(User $user): array
    {
        return match($user->orientation) {
            'heterosexuel' => match($user->genre) {
                'homme'       => ['femme'],
                'femme'       => ['homme'],
                default       => ['homme', 'femme', 'non-binaire', 'autre'],
            },
            'homosexuel'   => [$user->genre],
            'bisexuel'     => ['homme', 'femme', 'non-binaire'],
            'pansexuel'    => ['homme', 'femme', 'non-binaire', 'autre'],
            'autre'        => ['homme', 'femme', 'non-binaire', 'autre'],
            default        => [],
        };
    }
}
