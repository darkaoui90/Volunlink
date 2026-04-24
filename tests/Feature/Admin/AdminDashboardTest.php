<?php

namespace Tests\Feature\Admin;

use App\Models\Mission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_shows_dynamic_attendance_city_and_coverage_metrics(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $casablancaLead = User::factory()->create([
            'role' => User::ROLE_VOLUNTEER,
            'name' => 'Amina Casablanca',
            'city' => 'Casablanca',
            'languages' => 'Arabic, English, French',
            'skills' => 'First Aid, Logistics, Support',
        ]);

        $casablancaSupport = User::factory()->create([
            'role' => User::ROLE_VOLUNTEER,
            'city' => 'Casablanca',
        ]);

        $rabatVolunteer = User::factory()->create([
            'role' => User::ROLE_VOLUNTEER,
            'city' => 'Rabat',
        ]);

        $fullyCoveredMission = $this->createMission('Gate Support', 2);
        $fullyCoveredMission->volunteers()->attach($casablancaLead->id, ['status' => 'present']);
        $fullyCoveredMission->volunteers()->attach($casablancaSupport->id, ['status' => 'late', 'late_minutes' => 12]);

        $partiallyCoveredMission = $this->createMission('Transport Help', 3);
        $partiallyCoveredMission->volunteers()->attach($rabatVolunteer->id, ['status' => 'absent']);

        $this->createMission('Info Desk', 1);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertViewHas('attendanceRate', 67);
        $response->assertViewHas('citiesCount', 2);
        $response->assertViewHas('missionFillRate', 50);
        $response->assertViewHas('missionCoverage', [
            'covered' => 1,
            'partial' => 1,
            'uncovered' => 1,
        ]);
        $response->assertViewHas('volunteersByCity', function ($cities) {
            return $cities->values()->all() === [
                ['city' => 'Casablanca', 'total' => 2],
                ['city' => 'Rabat', 'total' => 1],
            ];
        });
        $response->assertSeeText('67%');
        $response->assertSeeText('Casablanca');
        $response->assertSeeText('Rabat');
        $response->assertSeeText('Arabic, English');
        $response->assertSeeText('First Aid, Logistics');
    }

    private function createMission(string $title, int $requiredVolunteers): Mission
    {
        return Mission::create([
            'title' => $title,
            'date' => '2030-06-10',
            'start_time' => '08:00',
            'end_time' => '12:00',
            'location' => 'Casablanca Stadium',
            'required_volunteers' => $requiredVolunteers,
        ]);
    }
}
