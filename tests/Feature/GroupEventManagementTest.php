<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupEventManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_group_admin_can_update_group_privacy(): void
    {
        $user = User::create([
            'nom' => 'Admin',
            'prenom' => 'Group',
            'email' => 'group-admin@edu.cegeptr.qc.ca',
            'password' => 'Password12',
            'position' => 'etudiant',
        ]);

        $group = Group::create([
            'creator_id' => $user->id,
            'nom' => 'Cinema Club',
            'description' => 'Old description',
            'est_public' => false,
        ]);

        $group->members()->attach($user->id, ['role' => 'admin']);

        $response = $this->actingAs($user)->put(route('groups.update', $group), [
            'nom' => 'Cinema Club 2',
            'description' => 'New description',
            'est_public' => '1',
        ]);

        $response->assertRedirect();

        $group->refresh();

        $this->assertSame('Cinema Club 2', $group->nom);
        $this->assertSame('New description', $group->description);
        $this->assertTrue($group->est_public);
    }

    public function test_event_creator_can_update_and_delete_event(): void
    {
        $user = User::create([
            'nom' => 'Owner',
            'prenom' => 'Event',
            'email' => 'event-owner@edu.cegeptr.qc.ca',
            'password' => 'Password12',
            'position' => 'etudiant',
        ]);

        $event = Event::create([
            'creator_id' => $user->id,
            'titre' => 'Board Games',
            'description' => 'Original description',
            'date_evenement' => now()->addDay()->toDateString(),
            'heure_debut' => '18:00',
            'lieu' => 'Room A',
            'max_participants' => 8,
            'prix' => 0,
            'type_acces' => 'public',
            'statut' => 'actif',
        ]);

        $event->participants()->attach($user->id, ['statut' => 'confirme']);

        $updateResponse = $this->actingAs($user)->put(route('events.update', $event), [
            'titre' => 'Board Games Night',
            'description' => 'Updated description',
            'date_evenement' => now()->addDays(2)->toDateString(),
            'heure_debut' => '19:30',
            'lieu' => 'Room B',
            'max_participants' => 10,
            'prix' => '5.00',
            'type_acces' => 'prive',
            'group_id' => '',
        ]);

        $updateResponse->assertRedirect();

        $event->refresh();

        $this->assertSame('Board Games Night', $event->titre);
        $this->assertSame('Updated description', $event->description);
        $this->assertSame('Room B', $event->lieu);
        $this->assertSame('prive', $event->type_acces);

        $deleteResponse = $this->actingAs($user)->delete(route('events.destroy', $event));

        $deleteResponse->assertRedirect(route('events.index'));
        $this->assertDatabaseMissing('events', ['id' => $event->id]);
    }
}
