<?php

namespace Tests\Feature\Admin;

use App\Models\Mission;
use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_site(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.sites.store'), [
            'name' => 'Casablanca Stadium',
            'city' => 'Casablanca',
            'type' => 'Stadium',
            'address' => 'Boulevard de la Resistance, Casablanca',
            'capacity' => 45000,
            'description' => 'Primary event stadium.',
            'latitude' => 33.57311,
            'longitude' => -7.58984,
        ]);

        $site = Site::query()->first();

        $response->assertRedirect(route('admin.sites.edit', $site));
        $this->assertNotNull($site);
        $this->assertDatabaseHas('sites', [
            'name' => 'Casablanca Stadium',
            'city' => 'Casablanca',
            'type' => 'Stadium',
        ]);
    }

    public function test_admin_can_create_a_mission_linked_to_a_site(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $site = Site::create([
            'name' => 'Rabat Media Center',
            'city' => 'Rabat',
            'type' => 'Media Center',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.missions.store'), [
            'title' => 'Accreditation Desk Assistance',
            'site_id' => $site->id,
            'date' => '2030-06-09',
            'start_time' => '09:00',
            'end_time' => '13:00',
            'required_volunteers' => 4,
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertDatabaseHas('missions', [
            'title' => 'Accreditation Desk Assistance',
            'site_id' => $site->id,
            'location' => 'Rabat Media Center',
        ]);
    }

    public function test_admin_cannot_delete_a_site_that_is_linked_to_missions(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $site = Site::create([
            'name' => 'Tangier Stadium',
            'city' => 'Tangier',
            'type' => 'Stadium',
        ]);

        Mission::create([
            'title' => 'Medical Point Support',
            'site_id' => $site->id,
            'date' => '2030-06-12',
            'start_time' => '10:00',
            'end_time' => '15:00',
            'location' => $site->name,
            'required_volunteers' => 3,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.sites.destroy', $site));

        $response->assertRedirect(route('admin.sites.edit', $site));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('sites', [
            'id' => $site->id,
        ]);
    }
}
