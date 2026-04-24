<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $totalUsers = User::count();
        $totalVolunteers = User::where('role', User::ROLE_VOLUNTEER)->count();
        $totalCoordinators = User::where('role', User::ROLE_COORDINATOR)->count();
        $totalSupervisors = User::where('role', User::ROLE_SUPERVISOR)->count();
        $volunteerSignupsThisMonth = User::query()
            ->where('role', User::ROLE_VOLUNTEER)
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();

        $recentUsers = User::query()
            ->select(['name', 'email', 'role', 'created_at'])
            ->latest()
            ->take(5)
            ->get();

        // Get latest 4 volunteer registrations
        $recentVolunteers = User::query()
            ->where('role', User::ROLE_VOLUNTEER)
            ->select(['name', 'email', 'city', 'languages', 'skills', 'created_at'])
            ->latest()
            ->take(4)
            ->get();

        $citiesCount = User::query()
            ->where('role', User::ROLE_VOLUNTEER)
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->count('city');

        $volunteersByCity = User::query()
            ->where('role', User::ROLE_VOLUNTEER)
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->select('city', DB::raw('COUNT(*) as total'))
            ->groupBy('city')
            ->orderByDesc('total')
            ->orderBy('city')
            ->get()
            ->map(fn ($item) => [
                'city' => $item->city,
                'total' => (int) $item->total,
            ]);

        $totalMissions = 0;
        $missionFillRate = 0;
        $missionCoverage = [
            'covered' => 0,
            'partial' => 0,
            'uncovered' => 0,
        ];

        if (Schema::hasTable('missions')) {
            $missions = Mission::query()
                ->withCount('volunteers')
                ->get(['id', 'required_volunteers']);

            $totalMissions = $missions->count();

            $totalRequiredVolunteers = $missions->sum(fn (Mission $mission) => (int) $mission->required_volunteers);
            $totalAssignedVolunteers = $missions->sum(fn (Mission $mission) => (int) $mission->volunteers_count);

            $missionFillRate = $totalRequiredVolunteers > 0
                ? min(100, (int) round(($totalAssignedVolunteers / $totalRequiredVolunteers) * 100))
                : 0;

            $missionCoverage = [
                'covered' => $missions->filter(
                    fn (Mission $mission) => (int) $mission->volunteers_count >= (int) $mission->required_volunteers
                )->count(),
                'partial' => $missions->filter(
                    fn (Mission $mission) => (int) $mission->volunteers_count > 0
                        && (int) $mission->volunteers_count < (int) $mission->required_volunteers
                )->count(),
                'uncovered' => $missions->filter(
                    fn (Mission $mission) => (int) $mission->volunteers_count === 0
                )->count(),
            ];
        }

        $presentCount = 0;
        $lateCount = 0;
        $absentCount = 0;
        $recordedAttendanceCount = 0;
        $attendanceRate = 0;

        if (Schema::hasTable('mission_user')) {
            $attendanceTotals = DB::table('mission_user')
                ->selectRaw("
                    SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_count,
                    SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as late_count,
                    SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent_count
                ")
                ->first();

            $presentCount = (int) ($attendanceTotals->present_count ?? 0);
            $lateCount = (int) ($attendanceTotals->late_count ?? 0);
            $absentCount = (int) ($attendanceTotals->absent_count ?? 0);
            $recordedAttendanceCount = $presentCount + $lateCount + $absentCount;

            $attendanceRate = $recordedAttendanceCount > 0
                ? (int) round((($presentCount + $lateCount) / $recordedAttendanceCount) * 100)
                : 0;
        }

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalVolunteers' => $totalVolunteers,
            'totalCoordinators' => $totalCoordinators,
            'totalSupervisors' => $totalSupervisors,
            'volunteerSignupsThisMonth' => $volunteerSignupsThisMonth,
            'totalMissions' => $totalMissions,
            'missionFillRate' => $missionFillRate,
            'missionCoverage' => $missionCoverage,
            'attendanceRate' => $attendanceRate,
            'recordedAttendanceCount' => $recordedAttendanceCount,
            'presentCount' => $presentCount,
            'lateCount' => $lateCount,
            'absentCount' => $absentCount,
            'citiesCount' => $citiesCount,
            'volunteersByCity' => $volunteersByCity,
            'recentUsers' => $recentUsers,
            'recentVolunteers' => $recentVolunteers,
        ]);
    }
}
