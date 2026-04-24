<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\MissionController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\SiteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Volunteer\MissionController as VolunteerMissionController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function (Request $request) {
        return redirect()->route($request->user()->dashboardRouteName());
    })->name('dashboard');

    Route::middleware('role:'.User::ROLE_ADMIN)
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
            Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

            Route::get('/missions/create', [MissionController::class, 'create'])->name('missions.create');
            Route::post('/missions', [MissionController::class, 'store'])->name('missions.store');
            Route::get('/missions/{mission}/assign', [MissionController::class, 'assign'])->name('missions.assign');
            Route::post('/missions/{mission}/assign', [MissionController::class, 'assignStore'])->name('missions.assign.store');
            Route::resource('missions', MissionController::class)->except(['create', 'store']);

            Route::get('/coordinators/create', function () {
                return redirect()
                    ->route('admin.dashboard')
                    ->with('status', 'Create Coordinator page will be added soon.');
            })->name('coordinators.create');

            Route::get('/supervisors/create', function () {
                return redirect()
                    ->route('admin.dashboard')
                    ->with('status', 'Create Supervisor page will be added soon.');
            })->name('supervisors.create');

            Route::get('/volunteers', function (Request $request) {
                $query = User::where('role', 'volunteer');

                // Search by name or email
                if ($request->filled('search')) {
                    $search = $request->input('search');
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
                }

                // Filter by city
                if ($request->filled('city')) {
                    $query->where('city', $request->input('city'));
                }

                // Filter by skills
                if ($request->filled('skills')) {
                    $skill = $request->input('skills');
                    $query->where('skills', 'like', "%{$skill}%");
                }

                $volunteers = $query->get();

                // Get unique cities from volunteers
                $cities = User::where('role', 'volunteer')
                    ->whereNotNull('city')
                    ->where('city', '!=', '')
                    ->distinct()
                    ->pluck('city')
                    ->sort()
                    ->values();

                // Get all unique skills from volunteers
                $allSkills = User::where('role', 'volunteer')
                    ->whereNotNull('skills')
                    ->where('skills', '!=', '')
                    ->pluck('skills');

                $skills = collect();
                foreach ($allSkills as $skillList) {
                    $individualSkills = explode(',', $skillList);
                    foreach ($individualSkills as $skill) {
                        $trimmedSkill = trim($skill);
                        if ($trimmedSkill) {
                            $skills->push($trimmedSkill);
                        }
                    }
                }
                $skills = $skills->unique()->sort()->values();

                return view('admin.volunteers.index', compact('volunteers', 'cities', 'skills'));
            })->name('volunteers.index');

            Route::get('/volunteers/{volunteer}/edit', function ($volunteer) {
                $volunteer = User::findOrFail($volunteer);

                return view('admin.volunteers.edit', compact('volunteer'));
            })->name('volunteers.edit');

            Route::put('/volunteers/{volunteer}', function (Request $request, $volunteer) {
                $volunteer = User::findOrFail($volunteer);
                $volunteer->update($request->only(['name', 'email', 'phone', 'city', 'languages', 'skills', 'availability', 'role']));

                return redirect()->route('admin.volunteers.index')->with('success', 'Volunteer updated successfully.');
            })->name('volunteers.update');

            Route::delete('/volunteers/{volunteer}', function ($volunteer) {
                $volunteer = User::findOrFail($volunteer);
                $volunteer->delete();

                return redirect()->route('admin.volunteers.index')->with('success', 'Volunteer deleted successfully.');
            })->name('volunteers.destroy');

            Route::get('/sites', [SiteController::class, 'index'])->name('sites.index');
            Route::post('/sites', [SiteController::class, 'store'])->name('sites.store');
            Route::get('/sites/{site}/edit', [SiteController::class, 'edit'])->name('sites.edit');
            Route::put('/sites/{site}', [SiteController::class, 'update'])->name('sites.update');
            Route::delete('/sites/{site}', [SiteController::class, 'destroy'])->name('sites.destroy');

            Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
            Route::get('/missions/{mission}/attendance', [AttendanceController::class, 'show'])->name('attendance.show');
            Route::patch('/missions/{mission}/attendance/{volunteer}', [AttendanceController::class, 'update'])->name('attendance.update');
        });

    Route::view('/coordinator/dashboard', 'dashboard')
        ->middleware('role:'.User::ROLE_COORDINATOR)
        ->name('coordinator.dashboard');

    Route::view('/supervisor/dashboard', 'dashboard')
        ->middleware('role:'.User::ROLE_SUPERVISOR)
        ->name('supervisor.dashboard');

    Route::view('/volunteer/dashboard', 'volunteer.dashboard')
        ->middleware('role:'.User::ROLE_VOLUNTEER)
        ->name('volunteer.dashboard');

    // Volunteer Mission Routes
    Route::middleware(['auth', 'verified', 'role:'.User::ROLE_VOLUNTEER])->prefix('volunteer/missions')->name('volunteer.missions.')->group(function () {
        Route::get('/{mission}', [VolunteerMissionController::class, 'show'])->name('show');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
