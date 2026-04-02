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
            'Passionne(e) de technologie et toujours partant(e) pour de nouveaux projets.',
            'J\'adore le plein air et cherche des gens pour faire des activites ensemble.',
            'Amateur(trice) de jeux de societe et de bonne compagnie.',
            'Musicien(ne) a mes heures, j\'aime partager ma passion.',
            'Fan de cinema et de cuisine, toujours pret(e) pour une sortie.',
            'Sportif(ve) dans l\'ame, cherche des partenaires d\'entrainement.',
            'Lecteur(trice) avide et curieux(se) de tout, j\'aime les discussions profondes.',
            'Creatif(ve) et passionne(e) d\'art, je cherche d\'autres artistes.',
            'Gamer convaincu(e), cherche des co-equipiers pour jouer ensemble.',
            'Amoureux(se) de la nature, randonnee et camping sont mes activites preferees.',
            'Cuisinier(e) amateur(trice), j\'adore tester de nouvelles recettes.',
            'Toujours a la recherche de nouvelles experiences et de nouvelles rencontres.',
            'Developpeur(se) web passionn(e) par les nouvelles technologies.',
            'Zen et curieux(se), j\'aime le yoga et la meditation.',
            'Etudiant(e) serieux(se) qui cherche aussi a s\'amuser et decouvrir.',
            'J\'aime la photo et les sorties culturelles — musees, expos, concerts.',
            'Fan de manga et de jeux de role, cherche des joueurs.',
            'Toujours partant(e) pour un match de soccer ou une partie de basketball.',
            'Amateur(trice) de musique electronique et de concerts.',
            'Je collectionne les hobbies et les nouvelles experiences.',
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
            'Techniques de travail social',
            'Communication',
        ];

        $typesConnexion = [
            ['amitie'],
            ['activites'],
            ['etudes'],
            ['sorties'],
            ['gaming'],
            ['amitie', 'activites'],
            ['amitie', 'sorties'],
            ['activites', 'sorties'],
            ['etudes', 'amitie'],
            ['gaming', 'amitie'],
            ['activites', 'etudes'],
            ['amitie', 'activites', 'sorties'],
            ['gaming', 'activites'],
            ['etudes', 'amitie', 'sorties'],
        ];

        $allPrenoms = array_merge(
            array_map(fn ($p) => [$p, 'etudiant'], $prenomsF),
            array_map(fn ($p) => [$p, 'etudiant'], $prenomsM),
            [
                ['Sophie', 'personnel'], ['Marc', 'personnel'], ['Julie', 'personnel'],
                ['Pierre', 'personnel'], ['Nathalie', 'personnel'], ['Frederic', 'personnel'],
                ['Melanie', 'personnel'], ['Jean', 'personnel'], ['Annie', 'personnel'], ['Claude', 'personnel'],
            ]
        );

        $allInterets = Interet::all();
        $used        = [];
        $count       = 0;
        $maxAttempts = 5000;
        $attempts    = 0;

        while ($count < 250 && $attempts < $maxAttempts) {
            $attempts++;

            $pick     = $allPrenoms[array_rand($allPrenoms)];
            $prenom   = $pick[0];
            $position = $pick[1];
            $nom      = $noms[array_rand($noms)];

            // Transliterate accented characters for email
            $trans = [
                'é'=>'e','è'=>'e','ê'=>'e','ë'=>'e','à'=>'a','â'=>'a','ä'=>'a',
                'î'=>'i','ï'=>'i','ô'=>'o','ö'=>'o','ù'=>'u','û'=>'u','ü'=>'u',
                'ç'=>'c','É'=>'e','È'=>'e','Ê'=>'e','À'=>'a','Â'=>'a','Î'=>'i',
                'Ô'=>'o','Ù'=>'u','Û'=>'u','Ç'=>'c',
            ];
            $slug  = strtolower(str_replace(array_keys($trans), array_values($trans), $prenom . '.' . $nom));
            $slug  = preg_replace('/[^a-z0-9.]/', '', $slug);
            $domain = $position === 'etudiant' ? 'edu.cegeptr.qc.ca' : 'cegeptr.qc.ca';
            $email = $slug . ($count > 0 && isset($used[$slug]) ? $count : '') . '@' . $domain;

            if (isset($used[$email])) continue;
            $used[$email] = true;
            $used[$slug]  = ($used[$slug] ?? 0) + 1;

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'nom'               => $nom,
                    'prenom'            => $prenom,
                    'password'          => Hash::make('Password1'),
                    'position'          => $position,
                    'numero_programme'  => $programmes[array_rand($programmes)],
                    'bio'               => $bios[array_rand($bios)],
                    'visibilite'        => rand(0, 5) === 0 ? 'prive' : 'public',
                    'email_verified_at' => now(),
                    'type_connexion'    => $typesConnexion[array_rand($typesConnexion)],
                ]
            );

            if ($allInterets->isNotEmpty()) {
                $ids = $allInterets->random(min(rand(3, 9), $allInterets->count()))->pluck('id')->toArray();
                $user->interets()->syncWithoutDetaching($ids);
            }

            $count++;
        }
    }
}
