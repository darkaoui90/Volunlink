<?php

namespace Tests\Feature\Admin;

use App\Models\Mission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_attendance_index(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);
        $volunteer = User::factory()->create([
            'role' => User::ROLE_VOLUNTEER,
        ]);
        $mission = $this->createMission();

        $mission->volunteers()->attach($volunteer->id, ['status' => 'assigned']);

        $response = $this->actingAs($admin)->get(route('admin.attendance.index'));

        $response->assertOk();
        $response->assertSee($mission->title);
        $response->assertSee('Manage');
    }

    public function test_admin_can_view_a_mission_attendance_page(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);
        $volunteer = User::factory()->create([
            'role' => User::ROLE_VOLUNTEER,
            'name' => 'Volunteer One',
        ]);
        $mission = $this->createMission();

        $mission->volunteers()->attach($volunteer->id, ['status' => 'assigned']);

        $response = $this->actingAs($admin)->get(route('admin.attendance.show', $mission));

        $response->assertOk();
        $response->assertSee($mission->title);
        $response->assertSee('Volunteer One');
        $response->assertSee('Assigned');
    }

    public function test_admin_can_update_attendance_status_for_an_assigned_volunteer(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);
        $volunteer = User::factory()->create([
            'role' => User::ROLE_VOLUNTEER,
        ]);
        $mission = $this->createMission();

        $mission->volunteers()->attach($volunteer->id, ['status' => 'assigned']);

        $response = $this->actingAs($admin)
            ->from(route('admin.attendance.show', $mission))
            ->patch(route('admin.attendance.update', [$mission, $volunteer]), [
                'status' => 'present',
            ]);

        $response->assertRedirect(route('admin.attendance.show', $mission));
        $this->assertDatabaseHas('mission_user', [
            'mission_id' => $mission->id,
            'user_id' => $volunteer->id,
            'status' => 'present',
            'late_minutes' => null,
        ]);
    }

    public function test_admin_can_record_late_hours_and_minutes_for_a_late_volunteer(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);
        $volunteer = User::factory()->create([
            'role' => User::ROLE_VOLUNTEER,
        ]);
        $mission = $this->createMission();

        $mission->volunteers()->attach($volunteer->id, ['status' => 'assigned']);

        $response = $this->actingAs($admin)->patch(route('admin.attendance.update', [$mission, $volunteer]), [
            'status' => 'late',
            'late_hours' => 1,
            'late_minutes' => 17,
        ]);

        $response->assertRedirect(route('admin.attendance.show', $mission));
        $this->assertDatabaseHas('mission_user', [
            'mission_id' => $mission->id,
            'user_id' => $volunteer->id,
            'status' => 'late',
            'late_minutes' => 77,
        ]);
    }

    public function test_admin_must_provide_late_time_when_marking_attendance_as_late(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);
        $volunteer = User::factory()->create([
            'role' => User::ROLE_VOLUNTEER,
        ]);
        $mission = $this->createMission();

        $mission->volunteers()->attach($volunteer->id, ['status' => 'assigned']);

        $response = $this->from(route('admin.attendance.show', $mission))
            ->actingAs($admin)
            ->patch(route('admin.attendance.update', [$mission, $volunteer]), [
                'status' => 'late',
            ]);

        $response->assertRedirect(route('admin.attendance.show', $mission));
        $response->assertSessionHasErrors(['late_hours', 'late_minutes']);
    }

    public function test_admin_cannot_mark_late_with_zero_hours_and_zero_minutes(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);
        $volunteer = User::factory()->create([
            'role' => User::ROLE_VOLUNTEER,
        ]);
        $mission = $this->createMission();

        $mission->volunteers()->attach($volunteer->id, ['status' => 'assigned']);

        $response = $this->from(route('admin.attendance.show', $mission))
            ->actingAs($admin)
            ->patch(route('admin.attendance.update', [$mission, $volunteer]), [
                'status' => 'late',
                'late_hours' => 0,
                'late_minutes' => 0,
            ]);

        $response->assertRedirect(route('admin.attendance.show', $mission));
        $response->assertSessionHasErrors('late_minutes');
        $this->assertDatabaseHas('mission_user', [
            'mission_id' => $mission->id,
            'user_id' => $volunteer->id,
            'status' => 'assigned',
            'late_minutes' => null,
        ]);
    }

    public function test_admin_clears_late_minutes_when_status_changes_from_late_to_present(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);
        $volunteer = User::factory()->create([
            'role' => User::ROLE_VOLUNTEER,
        ]);
        $mission = $this->createMission();

        $mission->volunteers()->attach($volunteer->id, [
            'status' => 'late',
            'late_minutes' => 12,
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.attendance.update', [$mission, $volunteer]), [
            'status' => 'present',
        ]);

        $response->assertRedirect(route('admin.attendance.show', $mission));
        $this->assertDatabaseHas('mission_user', [
            'mission_id' => $mission->id,
            'user_id' => $volunteer->id,
            'status' => 'present',
            'late_minutes' => null,
        ]);
    }

    public function test_admin_cannot_update_attendance_for_a_volunteer_not_assigned_to_the_mission(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);
        $volunteer = User::factory()->create([
            'role' => User::ROLE_VOLUNTEER,
        ]);
        $mission = $this->createMission();

        $response = $this->actingAs($admin)->patch(route('admin.attendance.update', [$mission, $volunteer]), [
            'status' => 'late',
        ]);

        $response->assertNotFound();
        $this->assertDatabaseMissing('mission_user', [
            'mission_id' => $mission->id,
            'user_id' => $volunteer->id,
            'status' => 'late',
        ]);
    }

    private function createMission(): Mission
    {
        return Mission::create([
            'title' => 'Stadium Gate Support',
            'date' => '2030-06-10',
            'start_time' => '08:00',
            'end_time' => '12:00',
            'location' => 'Casablanca Stadium',
            'required_volunteers' => 12,
        ]);
    }
}
