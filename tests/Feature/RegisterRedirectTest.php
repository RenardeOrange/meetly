<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_user_is_redirected_to_interests_after_registering(): void
    {
        $response = $this->post(route('register'), [
            'nom' => 'Tester',
            'prenom' => 'Casey',
            'email' => 'casey@edu.cegeptr.qc.ca',
            'password' => 'Password12',
            'password_confirmation' => 'Password12',
            'position' => 'etudiant',
        ]);

        $response
            ->assertRedirect(route('interets.index'))
            ->assertSessionHasNoErrors();

        $this->assertAuthenticated();
    }
}
