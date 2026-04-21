<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ModerationFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_report_another_account_and_admin_can_view_it(): void
    {
        $reporter = $this->makeUser('reporter@example.com');
        $reported = $this->makeUser('reported@example.com');
        $admin = $this->makeUser('admin@example.com', ['role' => 'admin']);

        $this->actingAs($reporter)
            ->postJson(route('profiles.report', $reported), [
                'reason' => 'Message inapproprie et comportement agressif.',
            ])
            ->assertOk()
            ->assertJson(['status' => 'reported']);

        $this->actingAs($admin)
            ->get(route('admin.flagged'))
            ->assertOk()
            ->assertSee('Message inapproprie et comportement agressif.')
            ->assertSee($reported->email);
    }

    public function test_user_can_block_and_unblock_someone(): void
    {
        $user = $this->makeUser('blocker@example.com');
        $target = $this->makeUser('blocked@example.com');

        $this->actingAs($user)
            ->postJson(route('profiles.block', $target))
            ->assertOk()
            ->assertJson(['status' => 'blocked']);

        $this->assertDatabaseHas('user_blocks', [
            'blocker_id' => $user->id,
            'blocked_id' => $target->id,
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee($target->prenom);

        $this->actingAs($user)
            ->delete(route('blocks.destroy', $target))
            ->assertRedirect();

        $this->assertDatabaseMissing('user_blocks', [
            'blocker_id' => $user->id,
            'blocked_id' => $target->id,
        ]);
    }

    protected function makeUser(string $email, array $overrides = []): User
    {
        return User::create(array_merge([
            'email' => $email,
            'password' => Hash::make('password'),
            'nom' => 'Test',
            'prenom' => 'User',
            'role' => 'user',
            'visibilite' => 'public',
            'blacklisted' => false,
            'position' => 'etudiant',
        ], $overrides));
    }
}
