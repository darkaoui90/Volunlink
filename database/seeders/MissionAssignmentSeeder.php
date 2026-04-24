<?php

namespace Database\Seeders;

use App\Models\Mission;
use App\Models\User;
use Illuminate\Database\Seeder;

class MissionAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $missions = Mission::query()
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        $volunteers = User::query()
            ->where('role', User::ROLE_VOLUNTEER)
            ->orderBy('id')
            ->get();

        if ($missions->isEmpty() || $volunteers->isEmpty()) {
            return;
        }

        $rotation = 0;
        $volunteerCount = $volunteers->count();

        foreach ($missions as $mission) {
            $assignmentCount = min(
                $volunteerCount,
                max(2, min($mission->required_volunteers, 4))
            );

            $selectedVolunteers = collect();

            for ($index = 0; $index < $assignmentCount; $index++) {
                $selectedVolunteers->push(
                    $volunteers[($rotation + $index) % $volunteerCount]
                );
            }

            foreach ($selectedVolunteers->unique('id') as $volunteer) {
                $alreadyAssigned = $mission->volunteers()
                    ->whereKey($volunteer->id)
                    ->exists();

                if ($alreadyAssigned) {
                    continue;
                }

                $mission->volunteers()->attach($volunteer->id, [
                    'status' => 'assigned',
                ]);
            }

            $rotation = ($rotation + 2) % $volunteerCount;
        }
    }
}
