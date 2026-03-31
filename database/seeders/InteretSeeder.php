<?php

namespace Database\Seeders;

use App\Models\Interet;
use Illuminate\Database\Seeder;

class InteretSeeder extends Seeder
{
    public function run(): void
    {
        $interets = [
            'Arts' => ['Musique', 'Peinture', 'Photographie', 'Cuisine', 'Dessin', 'Sculpture', 'Danse', 'Théâtre'],
            'Sports' => ['Course', 'Soccer', 'Vélo', 'Natation', 'Basketball', 'Volleyball', 'Hockey', 'Tennis'],
            'Jeux de société' => ['Jeux de cartes', 'Jeux de plateau', 'Jeux de rôle', 'Puzzles', 'Échecs'],
            'Gaming' => ['Jeux vidéo', 'Jeux en ligne', 'Jeux mobiles', 'Esports', 'Rétrogaming'],
            'Technologie' => ['Programmation', 'Intelligence artificielle', 'Robotique', 'Design web'],
            'Nature' => ['Randonnée', 'Camping', 'Jardinage', 'Astronomie'],
            'Lecture' => ['Romans', 'Manga', 'Bandes dessinées', 'Science-fiction', 'Fantasy'],
        ];

        foreach ($interets as $categorie => $noms) {
            foreach ($noms as $nom) {
                Interet::firstOrCreate(
                    ['nom' => $nom],
                    ['categorie' => $categorie]
                );
            }
        }
    }
}
