<?php

namespace Tests\Feature\Admin;

use App\Models\Mission;
use App\Models\Site;
use App\Models\User;
use App\Notifications\MissionCreatedNotification;
use App\Notifications\VolunteerJoinedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_receives_a_notification_when_a_mission_is_created(): void
    {
        Notification::fake();

        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $site = Site::query()->create([
            'name' => 'Grand Stadium',
            'city' => 'Casablanca',
            'type' => 'Stadium',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.missions.store'), [
            'title' => 'Fan Welcome Team',
            'site_id' => $site->id,
            'date' => '2030-06-15',
            'start_time' => '09:00',
            'end_time' => '13:00',
            'required_volunteers' => 10,
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        Notification::assertSentTo($admin, MissionCreatedNotification::class);
    }

    public function test_admin_dashboard_shows_recent_notifications_and_can_mark_them_as_read(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'name' => 'Mohammed Darkaoui',
        ]);

        $volunteer = User::factory()->create([
            'role' => User::ROLE_VOLUNTEER,
            'name' => 'Leah Randall',
            'city' => 'Fes',
        ]);

        $mission = Mission::query()->create([
            'title' => 'Arrival Support',
            'date' => '2030-06-20',
            'start_time' => '08:00',
            'end_time' => '12:00',
            'location' => 'Rabat Volunteer Center',
            'required_volunteers' => 6,
        ]);

        $admin->notify(new VolunteerJoinedNotification($volunteer));
        $admin->notify(new MissionCreatedNotification($mission, $admin->name));

        $dashboardResponse = $this->actingAs($admin)->get(route('admin.dashboard'));

        $dashboardResponse->assertOk();
        $dashboardResponse->assertSeeText('Notifications');
        $dashboardResponse->assertSeeText('New volunteer joined');
        $dashboardResponse->assertSeeText('New mission created');
        $dashboardResponse->assertSeeText('Mark all as read');
        $dashboardResponse->assertSeeText('Sign out');

        $markReadResponse = $this->actingAs($admin)->post(route('admin.notifications.read-all'));

        $markReadResponse->assertRedirect();
        $markReadResponse->assertSessionHas('success', 'Notifications marked as read.');
        $this->assertSame(0, $admin->fresh()->unreadNotifications()->count());
    }

    public function test_admin_dashboard_keeps_the_empty_notification_dropdown_clickable_without_empty_state_text(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $dashboardResponse = $this->actingAs($admin)->get(route('admin.dashboard'));

        $dashboardResponse->assertOk();
        $dashboardResponse->assertSeeText('Notifications');
        $dashboardResponse->assertDontSeeText('Everything is up to date.');
        $dashboardResponse->assertDontSeeText('No notifications yet');
        $dashboardResponse->assertDontSeeText('0 recent update(s)');
    }

    public function test_admin_dashboard_still_loads_when_notifications_table_is_missing(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        Schema::dropIfExists('notifications');

        $dashboardResponse = $this->actingAs($admin)->get(route('admin.dashboard'));

        $dashboardResponse->assertOk();
        $dashboardResponse->assertSeeText('Notifications are not ready yet');
        $dashboardResponse->assertSeeText('php artisan migrate');

        $markReadResponse = $this->actingAs($admin)->post(route('admin.notifications.read-all'));

        $markReadResponse->assertRedirect();
        $markReadResponse->assertSessionHas('error', 'Notifications are not available until the database migration is run.');
    }
}
