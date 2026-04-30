<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ForgotPasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_reset_password_directly_from_forgot_password_page(): void
    {
        $user = User::create([
            'nom' => 'Testeur',
            'prenom' => 'Alex',
            'email' => 'alex@edu.cegeptr.qc.ca',
            'password' => 'OldPassword1',
            'position' => 'etudiant',
        ]);

        $response = $this->post(route('password.email'), [
            'email' => $user->email,
            'password' => 'NewPassword1',
            'password_confirmation' => 'NewPassword1',
        ]);

        $response
            ->assertRedirect(route('login'))
            ->assertSessionHas('status');

        $user->refresh();

        $this->assertTrue(Hash::check('NewPassword1', $user->password));
        $this->assertFalse(Hash::check('OldPassword1', $user->password));
    }
}
