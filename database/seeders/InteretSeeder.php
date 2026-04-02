<?php

namespace Database\Seeders;

use App\Models\Interet;
use Illuminate\Database\Seeder;

class InteretSeeder extends Seeder
{
    public function run(): void
    {
        $interets = [
            'Arts' => [
                'Musique', 'Peinture', 'Photographie', 'Cuisine', 'Danse',
                'Dessin', 'Sculpture', 'Calligraphie', 'Theatre', 'Chant',
                'Tricot', 'Couture', 'Poterie', 'Illustration', 'BD et manga',
            ],
            'Sports' => [
                'Course', 'Soccer', 'Velo', 'Basketball', 'Hockey',
                'Natation', 'Tennis', 'Volleyball', 'Escalade', 'Ski',
                'Badminton', 'Athletisme', 'Boxe', 'Arts martiaux', 'Golf',
                'Planche a roulettes', 'Surf des neiges',
            ],
            'Jeux de societe' => [
                'Jeux de cartes', 'Jeux de plateau', 'Jeux de role',
                'Echecs', 'Jeux de des', 'Jeux de strategie', 'Puzzles',
                'Jeux de dexterite', 'Jeux cooperatifs',
            ],
            'Gaming' => [
                'Gaming', 'Jeux video', 'Esports', 'RPG', 'FPS',
                'Jeux de simulation', 'Jeux de course', 'Jeux de sport',
                'Jeux d\'aventure', 'Jeux de construction',
            ],
            'Nature et plein air' => [
                'Randonnee', 'Camping', 'Jardinage', 'Astronomie',
                'Observation des oiseaux', 'Kayak', 'Peche', 'Chasse',
                'Cyclotourisme', 'Equitation',
            ],
            'Technologie' => [
                'Programmation', 'Intelligence artificielle', 'Cybersecurite',
                'Robotique', 'Electronique', 'Impression 3D', 'Developpement web',
                'Applications mobiles', 'Jeux video independants',
            ],
            'Lecture et culture' => [
                'Lecture', 'Ecriture', 'Science-fiction', 'Histoire',
                'Philosophie', 'Bandes dessinees', 'Poesie', 'Biographies',
                'Romans policiers',
            ],
            'Bien-etre' => [
                'Yoga', 'Meditation', 'Nutrition', 'Psychologie',
                'Pleine conscience', 'Course a pied', 'Fitness',
            ],
            'Musique' => [
                'Guitare', 'Piano', 'Batterie', 'Basse', 'Violon',
                'Chant choral', 'DJ', 'Composition musicale', 'Rap',
                'Musique classique', 'Jazz', 'Musique electronique',
            ],
            'Cinema et media' => [
                'Cinema', 'Series televisees', 'Documentaires', 'Animation',
                'Photographie de rue', 'Videographie', 'Podcasts',
                'Critiques de films',
            ],
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
