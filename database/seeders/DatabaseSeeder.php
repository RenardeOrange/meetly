<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@cegeptr.qc.ca'],
            [
                'nom' => 'Admin',
                'prenom' => 'Meetly',
                'password' => 'Admin123',
                'role' => 'admin',
                'position' => 'personnel',
            ]
        );

        $this->call(InteretSeeder::class);
    }
}
