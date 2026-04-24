<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(): View
    {
        $missions = Mission::query()
            ->whereHas('volunteers')
            ->with('site')
            ->with(['volunteers' => function ($query) {
                $query->orderBy('name');
            }])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        $stats = [
            'total' => $missions->sum(fn (Mission $mission) => $mission->volunteers->count()),
            'present' => $missions->sum(fn (Mission $mission) => $mission->volunteers->where('pivot.status', 'present')->count()),
            'absent' => $missions->sum(fn (Mission $mission) => $mission->volunteers->where('pivot.status', 'absent')->count()),
            'late' => $missions->sum(fn (Mission $mission) => $mission->volunteers->where('pivot.status', 'late')->count()),
        ];

        return view('admin.attendance.index', compact('missions', 'stats'));
    }

    public function show(Mission $mission): View
    {
        $mission->load(['site', 'volunteers' => function ($query) {
            $query->orderBy('name');
        }]);

        return view('admin.attendance.show', compact('mission'));
    }

    public function update(Request $request, Mission $mission, User $volunteer): RedirectResponse
    {
        abort_unless(
            $mission->volunteers()->whereKey($volunteer->id)->exists(),
            404
        );

        $validated = $request->validate([
            'status' => ['required', 'in:assigned,present,absent,late'],
            'late_hours' => ['nullable', 'integer', 'min:0', 'required_if:status,late'],
            'late_minutes' => ['nullable', 'integer', 'min:0', 'max:59', 'required_if:status,late'],
        ]);

        $totalLateMinutes = null;

        if ($validated['status'] === 'late') {
            $totalLateMinutes = ((int) $validated['late_hours'] * 60) + (int) $validated['late_minutes'];

            if ($totalLateMinutes < 1) {
                return back()
                    ->withErrors(['late_minutes' => 'Late time must be at least 1 minute.'])
                    ->withInput();
            }
        }

        $mission->volunteers()->updateExistingPivot($volunteer->id, [
            'status' => $validated['status'],
            'late_minutes' => $totalLateMinutes,
        ]);

        return redirect()
            ->route('admin.attendance.show', $mission)
            ->with('success', 'Attendance updated successfully.');
    }
}
