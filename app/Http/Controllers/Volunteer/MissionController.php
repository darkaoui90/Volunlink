<?php

namespace App\Http\Controllers\Volunteer;

use App\Http\Controllers\Controller;
use App\Models\Mission;
use Illuminate\Http\Response;

class MissionController extends Controller
{
    /**
     * Display the specified mission details.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $user = auth()->user();

        // Find the mission
        $mission = Mission::with(['site', 'volunteers'])->findOrFail($id);

        // Check if the volunteer is assigned to this mission
        if (! $user->missions()->where('mission_id', $mission->id)->exists()) {
            abort(403, 'You are not assigned to this mission.');
        }

        return view('volunteer.missions.show', compact('mission'));
    }
}
