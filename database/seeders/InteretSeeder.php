<?php

namespace Database\Seeders;

use App\Models\Interet;
use Illuminate\Database\Seeder;

class InteretSeeder extends Seeder
{
    public function run(): void
    {
        $interets = [
            'Arts' => ['Musique', 'Peinture', 'Photographie', 'Cuisine'],
            'Sports' => ['Course', 'Soccer', 'Velo'],
            'Jeux de societe' => ['Jeux de cartes', 'Jeux de plateau', 'Jeux de role'],
            'Gaming' => ['Gaming', 'Jeux video', 'Esports'],
        ];

        foreach ($interets as $categorie => $noms) {
            foreach ($noms as $nom) {
                Interet::updateOrCreate(
                    ['nom' => $nom],
                    ['categorie' => $categorie]
                );
            }
        }
    }
}
