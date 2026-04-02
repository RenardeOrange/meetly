<?php

namespace Database\Seeders;

use App\Models\Interet;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $prenomsF = [
            'Emma','Olivia','Charlotte','Amelia','Sophia','Evelyn','Abigail','Ella','Scarlett','Grace',
            'Chloe','Zoey','Lily','Penelope','Layla','Riley','Zoe','Nora','Mia','Harper',
            'Camille','Juliette','Amelie','Lea','Clara','Alice','Lucie','Marion','Pauline','Manon',
            'Eloise','Mathilde','Jeanne','Oceane','Emilie','Sarah','Laura','Julie','Marie','Anne',
            'Isabelle','Stephanie','Valerie','Sandrine','Nathalie','Catherine','Melanie','Audrey','Vanessa','Beatrice',
        ];

        $prenomsM = [
            'Liam','Noah','William','James','Benjamin','Ethan','Alexander','Henry','Sebastian','Jack',
            'Aiden','Owen','Jayden','Gabriel','Mateo','Isaac','Levi','Mason','Elijah','Lucas',
            'Felix','Antoine','Thomas','Simon','Samuel','Nicolas','Alexandre','Alexis','Maxime','Philippe',
            'Francois','Guillaume','Julien','Vincent','Remi','Sebastien','Mathieu','Tristan','Raphael','Christophe',
            'Patrick','Daniel','Eric','Marc','Pierre','Jean','Claude','Alain','Robert','Michel',
        ];

        $noms = [
            'Tremblay','Gagnon','Roy','Cote','Bouchard','Fortin','Gauthier','Morin','Lavoie','Pelletier',
            'Ouellet','Leblanc','Charbonneau','Beaulieu','Girard','Bergeron','Perreault','Dupont','Arsenault','Michaud',
            'Paquin','Dube','Simard','Lepage','Fontaine','Poulin','Thibodeau','Roux','Marquis','Dion',
            'Nadeau','Plante','Vachon','Turcotte','Lemieux','Belanger','Comeau','Boudreau','Caron','Mercier',
            'Allard','Villeneuve','Corriveau','Landry','Houde','Paradis','Blais','Bernier','Lapierre','Tessier',
            'Boutin','Cloutier','Desrosiers','Dionne','Gendron','Grenier','Hebert','Lachance','Laflamme','Lapointe',
            'Laroche','Lefebvre','Lemaire','Levesque','Maltais','Paquette','Patry','Poirier','Rivard','Roberge',
        ];

        $bios = [
            'Passionne(e) de technologie et amateur(trice) de cafe.',
            'Toujours en quete de nouvelles aventures et de nouvelles rencontres.',
            'Amateur(trice) de plein air et de jeux de societe.',
            'Musicien(ne) le soir, etudiant(e) le jour.',
            'Fan de cinema et de bonne cuisine.',
            'Sportif(ve) dans l\'ame, toujours pret(e) pour un defi.',
            'Lecteur(trice) avide et curieux(se) de tout.',
            'Creatif(ve) et passionne(e) d\'art sous toutes ses formes.',
            'Geek fier(re) et joueur(se) de jeux video.',
            'Amoureux(se) de la nature et de la randonnee.',
            'Cuisinier(e) amateur(trice) qui aime partager ses recettes.',
            'Toujours a la recherche de nouvelles experiences culturelles.',
            'Developpeur(se) web le jour, gamer la nuit.',
            'Yogiste et meditatif(ve) dans mes temps libres.',
            'Passionne(e) par la psychologie et le bien-etre.',
            'J\'aime la photographie et les sorties en nature.',
            'Fan de manga et de jeux de role autour d\'une table.',
            'Je cherche des gens avec qui partager mes passions.',
            'Etudiant(e) serieux(se) qui sait aussi s\'amuser.',
            'Curieux(se) de tout, je collectionne les hobbies.',
        ];

        $programmes = [
            'Techniques de l\'informatique',
            'Sciences humaines',
            'Arts et lettres',
            'Techniques de comptabilite',
            'Sciences de la nature',
            'Musique',
            'Administration des affaires',
            'Arts visuels',
            'Histoire et civilisation',
            'Electronique industrielle',
            'Techniques de dietetique',
            'Techniques d\'education a l\'enfance',
            'Design de mode',
            'Lettres',
            'Soins infirmiers',
            'Mathematiques',
            'Physique',
            'Chimie',
        ];

        // Orientation distribution: ~75% hetero, ~8% homo, ~12% bi, ~3% pan, ~2% autre
        $orientations = array_merge(
            array_fill(0, 75, 'heterosexuel'),
            array_fill(0, 8, 'homosexuel'),
            array_fill(0, 12, 'bisexuel'),
            array_fill(0, 3, 'pansexuel'),
            array_fill(0, 2, 'autre'),
        );

        $typesRelation = [
            ['amitie'],
            ['romantique_serieux'],
            ['romantique_casual'],
            ['activites'],
            ['amitie', 'activites'],
            ['romantique_serieux', 'amitie'],
            ['romantique_casual', 'amitie'],
            ['romantique_serieux', 'romantique_casual'],
            ['amitie', 'romantique_serieux', 'activites'],
        ];

        $allInterets = Interet::all();

        $used = [];
        $count = 0;
        $maxAttempts = 3000;
        $attempts = 0;

        // ~47% femme, ~47% homme, ~6% non-binaire/autre
        $genrePool = array_merge(
            array_fill(0, 47, 'femme'),
            array_fill(0, 47, 'homme'),
            array_fill(0, 4,  'non-binaire'),
            array_fill(0, 2,  'autre'),
        );

        while ($count < 250 && $attempts < $maxAttempts) {
            $attempts++;
            $genre = $genrePool[array_rand($genrePool)];

            if ($genre === 'femme') {
                $prenom = $prenomsF[array_rand($prenomsF)];
            } elseif ($genre === 'homme') {
                $prenom = $prenomsM[array_rand($prenomsM)];
            } else {
                $prenom = (rand(0, 1) ? $prenomsF : $prenomsM)[array_rand($prenomsF)];
            }

            $nom = $noms[array_rand($noms)];

            // Transliterate accented characters
            $slug = strtolower(str_replace(
                ['é','è','ê','ë','à','â','ä','î','ï','ô','ö','ù','û','ü','ç','É','È','Ê','À','Â','Î','Ô','Ù','Û','Ç'],
                ['e','e','e','e','a','a','a','i','i','o','o','u','u','u','c','e','e','e','a','a','i','o','u','u','c'],
                $prenom . '.' . $nom
            ));
            $slug = preg_replace('/[^a-z0-9.]/', '', $slug);

            $position = rand(0, 5) === 0 ? 'personnel' : 'etudiant';
            $domain   = $position === 'etudiant' ? 'edu.cegeptr.qc.ca' : 'cegeptr.qc.ca';
            $suffix   = $count > 0 && in_array($slug, $used) ? $count : '';
            $email    = $slug . $suffix . '@' . $domain;

            if (in_array($email, $used)) {
                continue;
            }
            $used[] = $email;
            $used[] = $slug;

            $orientation = $orientations[array_rand($orientations)];

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'nom'               => $nom,
                    'prenom'            => $prenom,
                    'password'          => Hash::make('Password1'),
                    'position'          => $position,
                    'numero_programme'  => $programmes[array_rand($programmes)],
                    'bio'               => $bios[array_rand($bios)],
                    'visibilite'        => rand(0, 4) === 0 ? 'prive' : 'public',
                    'email_verified_at' => now(),
                    'genre'             => $genre,
                    'orientation'       => $orientation,
                    'type_relation'     => $typesRelation[array_rand($typesRelation)],
                ]
            );

            if ($allInterets->isNotEmpty()) {
                $interetCount = rand(3, 9);
                $ids = $allInterets->random(min($interetCount, $allInterets->count()))->pluck('id')->toArray();
                $user->interets()->syncWithoutDetaching($ids);
            }

            $count++;
        }
    }
}
