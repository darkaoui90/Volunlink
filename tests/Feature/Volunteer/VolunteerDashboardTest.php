<?php

namespace Tests\Feature\Volunteer;

use App\Models\Mission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VolunteerDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_volunteer_dashboard_shows_absence_late_counts_and_total_late_minutes(): void
    {
        $volunteer = User::factory()->create([
            'role' => User::ROLE_VOLUNTEER,
            'name' => 'Volunteer Stats',
        ]);

        $this->attachMission($volunteer, 'Absence Mission', '2030-06-01', 'absent');
        $this->attachMission($volunteer, 'Late Mission One', '2030-06-02', 'late', 21);
        $this->attachMission($volunteer, 'Late Mission Two', '2030-06-03', 'late', 37);
        $this->attachMission($volunteer, 'Present Mission', '2030-06-04', 'present');

        $response = $this->actingAs($volunteer)->get(route('volunteer.dashboard'));

        $response->assertOk();
        $response->assertSeeTextInOrder([
            'Total Absences',
            '1',
            'Late Attendances',
            '2',
            'Total Late Minutes',
            '58',
        ]);
    }

    private function attachMission(User $volunteer, string $title, string $date, string $status, ?int $lateMinutes = null): void
    {
        $mission = Mission::create([
            'title' => $title,
            'date' => $date,
            'start_time' => '08:00',
            'end_time' => '12:00',
            'location' => 'Casablanca Stadium',
            'required_volunteers' => 4,
        ]);

        $mission->volunteers()->attach($volunteer->id, [
            'status' => $status,
            'late_minutes' => $lateMinutes,
        ]);
    }
}
