<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfilePasswordUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_their_password_from_profile(): void
    {
        $user = User::create([
            'nom' => 'Testeur',
            'prenom' => 'Marie',
            'email' => 'marie@edu.cegeptr.qc.ca',
            'password' => 'OldPassword1',
            'position' => 'etudiant',
            'visibilite' => 'public',
        ]);

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'nom' => $user->nom,
            'prenom' => $user->prenom,
            'numero_programme' => 'Tech info',
            'bio' => 'Profil mis a jour',
            'visibilite' => 'public',
            'langue' => 'fr',
            'current_password' => 'OldPassword1',
            'password' => 'NewPassword1',
            'password_confirmation' => 'NewPassword1',
        ]);

        $response
            ->assertRedirect(route('profile.edit'))
            ->assertSessionHasNoErrors();

        $user->refresh();

        $this->assertTrue(Hash::check('NewPassword1', $user->password));
        $this->assertFalse(Hash::check('OldPassword1', $user->password));
    }
}
