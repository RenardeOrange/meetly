<?php

namespace Tests\Unit;

use App\Models\Interet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MatchScoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_match_score_counts_exact_matches_similar_categories_and_dominant_bonus(): void
    {
        $user = User::create([
            'nom' => 'Alpha',
            'prenom' => 'Alex',
            'email' => 'alex@edu.cegeptr.qc.ca',
            'password' => 'Password12',
            'position' => 'etudiant',
        ]);

        $other = User::create([
            'nom' => 'Bravo',
            'prenom' => 'Blair',
            'email' => 'blair@edu.cegeptr.qc.ca',
            'password' => 'Password12',
            'position' => 'etudiant',
        ]);

        $hockey = Interet::create(['nom' => 'Hockey', 'categorie' => 'Sports']);
        $running = Interet::create(['nom' => 'Running', 'categorie' => 'Sports']);
        $chess = Interet::create(['nom' => 'Chess', 'categorie' => 'Games']);
        $deckHockey = Interet::create(['nom' => 'Deck hockey', 'categorie' => 'Sports']);
        $sudoku = Interet::create(['nom' => 'Sudoku', 'categorie' => 'Games']);

        $user->interets()->attach([$hockey->id, $running->id, $chess->id]);
        $other->interets()->attach([$hockey->id, $deckHockey->id, $sudoku->id]);

        $user->load('interets');
        $other->load('interets');

        $this->assertSame(2.7, $user->matchScore($other));
    }

    public function test_match_score_is_zero_without_exact_or_similar_interest_signals(): void
    {
        $user = User::create([
            'nom' => 'Charlie',
            'prenom' => 'Casey',
            'email' => 'casey@edu.cegeptr.qc.ca',
            'password' => 'Password12',
            'position' => 'etudiant',
        ]);

        $other = User::create([
            'nom' => 'Delta',
            'prenom' => 'Drew',
            'email' => 'drew@edu.cegeptr.qc.ca',
            'password' => 'Password12',
            'position' => 'etudiant',
        ]);

        $ceramics = Interet::create(['nom' => 'Ceramics', 'categorie' => 'Arts']);
        $robotics = Interet::create(['nom' => 'Robotics', 'categorie' => 'Tech']);

        $user->interets()->attach([$ceramics->id]);
        $other->interets()->attach([$robotics->id]);

        $user->load('interets');
        $other->load('interets');

        $this->assertSame(0.0, $user->matchScore($other));
    }
}
