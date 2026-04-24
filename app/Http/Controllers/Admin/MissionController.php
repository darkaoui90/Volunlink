<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Models\Site;
use App\Models\User;
use App\Notifications\MissionCreatedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class MissionController extends Controller
{
    public function index(): View
    {
        $siteFilter = request('site');
        $statusFilter = request('status');
        $search = trim((string) request('search'));
        $today = now()->toDateString();
        $currentTime = now()->format('H:i:s');

        $missions = Mission::query()
            ->with(['volunteers:id', 'site:id,name,city,type'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($missionQuery) use ($search) {
                    $missionQuery
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%")
                        ->orWhereHas('site', function ($siteQuery) use ($search) {
                            $siteQuery
                                ->where('name', 'like', "%{$search}%")
                                ->orWhere('city', 'like', "%{$search}%");
                        });
                });
            })
            ->when($siteFilter, fn ($query) => $query->where('site_id', $siteFilter))
            ->when($statusFilter === 'upcoming', function ($query) use ($today, $currentTime) {
                $query->where(function ($statusQuery) use ($today, $currentTime) {
                    $statusQuery
                        ->whereDate('date', '>', $today)
                        ->orWhere(function ($sameDayQuery) use ($today, $currentTime) {
                            $sameDayQuery
                                ->whereDate('date', $today)
                                ->whereTime('start_time', '>', $currentTime);
                        });
                });
            })
            ->when($statusFilter === 'ongoing', function ($query) use ($today, $currentTime) {
                $query
                    ->whereDate('date', $today)
                    ->whereTime('start_time', '<=', $currentTime)
                    ->whereTime('end_time', '>=', $currentTime);
            })
            ->when($statusFilter === 'completed', function ($query) use ($today, $currentTime) {
                $query->where(function ($statusQuery) use ($today, $currentTime) {
                    $statusQuery
                        ->whereDate('date', '<', $today)
                        ->orWhere(function ($sameDayQuery) use ($today, $currentTime) {
                            $sameDayQuery
                                ->whereDate('date', $today)
                                ->whereTime('end_time', '<', $currentTime);
                        });
                });
            })
            ->orderByDesc('date')
            ->orderBy('start_time')
            ->paginate(10)
            ->withQueryString();

        $sites = Site::query()
            ->orderBy('city')
            ->orderBy('name')
            ->get(['id', 'name', 'city']);

        return view('admin.missions.index', compact('missions', 'sites'));
    }

    public function create(): View
    {
        $sites = Site::query()
            ->orderBy('city')
            ->orderBy('name')
            ->get(['id', 'name', 'city']);

        return view('admin.missions.create', compact('sites'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required',
            'site_id' => ['required', 'exists:sites,id'],
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'required_volunteers' => 'required|integer|min:1',
        ]);

        $data['location'] = $this->resolveLocationFromSiteId((int) $data['site_id']);

        $mission = Mission::create($data);

        $admins = User::query()
            ->where('role', User::ROLE_ADMIN)
            ->get();

        Notification::send(
            $admins,
            new MissionCreatedNotification($mission->load('site'), (string) $request->user()->name)
        );

        return redirect()
            ->route('admin.dashboard')
            ->with('status', 'Mission created successfully.');
    }

    public function show(Mission $mission): View
    {
        $mission->load(['site', 'volunteers']);

        return view('admin.missions.show', compact('mission'));
    }

    public function edit(Mission $mission): View
    {
        $mission->load('site');

        $sites = Site::query()
            ->orderBy('city')
            ->orderBy('name')
            ->get(['id', 'name', 'city']);

        return view('admin.missions.edit', compact('mission', 'sites'));
    }

    public function update(Request $request, Mission $mission): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'site_id' => ['required', 'exists:sites,id'],
            'description' => ['nullable', 'string'],
            'date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'required_volunteers' => ['required', 'integer', 'min:1'],
        ]);

        $validated['location'] = $this->resolveLocationFromSiteId((int) $validated['site_id']);

        $mission->update($validated);

        return redirect()
            ->route('admin.missions.index')
            ->with('status', 'Mission updated successfully.');
    }

    public function destroy(Mission $mission): RedirectResponse
    {
        $mission->delete();

        return redirect()
            ->route('admin.missions.index')
            ->with('status', 'Mission deleted successfully.');
    }

    public function assign(Mission $mission): View
    {
        $mission->load(['site', 'volunteers:id']);

        $volunteers = User::query()
            ->where('role', User::ROLE_VOLUNTEER)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('admin.missions.assign', compact('mission', 'volunteers'));
    }

    public function assignStore(Request $request, Mission $mission): RedirectResponse
    {
        $validated = $request->validate([
            'volunteers' => 'required|array',
            'volunteers.*' => 'exists:users,id',
        ]);

        $mission->volunteers()->sync($validated['volunteers']);

        return redirect()
            ->route('admin.missions.assign', $mission)
            ->with('status', 'Volunteers assigned successfully!');
    }

    public function storeAssignment(Request $request, Mission $mission): RedirectResponse
    {
        $validated = $request->validate([
            'volunteers' => ['nullable', 'array'],
            'volunteers.*' => ['integer', 'exists:users,id'],
        ]);

        $volunteerIds = collect($validated['volunteers'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        $validVolunteerIds = User::query()
            ->where('role', User::ROLE_VOLUNTEER)
            ->whereIn('id', $volunteerIds)
            ->pluck('id')
            ->all();

        $assignedCount = 0;

        foreach ($validVolunteerIds as $volunteerId) {
            $alreadyAssigned = $mission->volunteers()
                ->where('users.id', $volunteerId)
                ->exists();

            if ($alreadyAssigned) {
                continue;
            }

            $hasConflict = Mission::query()
                ->where('id', '!=', $mission->id)
                ->whereDate('date', $mission->date)
                ->whereTime('start_time', '<', $mission->end_time)
                ->whereTime('end_time', '>', $mission->start_time)
                ->whereHas('volunteers', function ($query) use ($volunteerId) {
                    $query->where('users.id', $volunteerId);
                })
                ->exists();

            if ($hasConflict) {
                continue;
            }

            $mission->volunteers()->attach($volunteerId, ['status' => 'assigned']);
            $assignedCount++;
        }

        return redirect()
            ->route('admin.missions.assign', $mission)
            ->with('status', $assignedCount.' volunteer(s) assigned successfully.');
    }

    private function resolveLocationFromSiteId(int $siteId): string
    {
        $site = Site::query()->findOrFail($siteId);

        return $site->name;
    }
}
