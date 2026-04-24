<?php

namespace Database\Seeders;

use App\Models\Mission;
use App\Models\Site;
use Illuminate\Database\Seeder;

class MissionSeeder extends Seeder
{
    public function run(): void
    {
        $missions = [
            [
                'title' => 'Stadium Entry Support',
                'description' => 'Guide spectators at the main entrance, verify directions, and keep entry lanes organized.',
                'date' => '2030-06-08',
                'start_time' => '08:00',
                'end_time' => '12:00',
                'location' => 'Casablanca Stadium',
                'required_volunteers' => 5,
            ],
            [
                'title' => 'Accreditation Desk Assistance',
                'description' => 'Help guests and staff at the accreditation desk with badge pickup and queue support.',
                'date' => '2030-06-09',
                'start_time' => '09:00',
                'end_time' => '13:00',
                'location' => 'Rabat Media Center',
                'required_volunteers' => 4,
            ],
            [
                'title' => 'Transport Coordination',
                'description' => 'Support shuttle arrivals and departures between volunteer pickup points and event sites.',
                'date' => '2030-06-10',
                'start_time' => '07:30',
                'end_time' => '11:30',
                'location' => 'Marrakech Shuttle Hub',
                'required_volunteers' => 6,
            ],
            [
                'title' => 'Fan Zone Hospitality',
                'description' => 'Welcome visitors, answer common questions, and direct attendees around the fan zone.',
                'date' => '2030-06-11',
                'start_time' => '14:00',
                'end_time' => '18:00',
                'location' => 'Agadir Fan Zone',
                'required_volunteers' => 4,
            ],
            [
                'title' => 'Medical Point Support',
                'description' => 'Assist the medical point team with crowd guidance and light administrative support.',
                'date' => '2030-06-12',
                'start_time' => '10:00',
                'end_time' => '15:00',
                'location' => 'Tangier Stadium',
                'required_volunteers' => 3,
            ],
            [
                'title' => 'Operations Center Setup',
                'description' => 'Prepare equipment, signage, and logistics materials for the next event day.',
                'date' => '2030-06-13',
                'start_time' => '15:00',
                'end_time' => '20:00',
                'location' => 'Casablanca Operations Center',
                'required_volunteers' => 6,
            ],
        ];

        foreach ($missions as $mission) {
            $site = Site::query()
                ->where('name', $mission['location'])
                ->first();

            Mission::updateOrCreate(
                [
                    'title' => $mission['title'],
                    'date' => $mission['date'],
                ],
                [
                    ...$mission,
                    'site_id' => $site?->id,
                    'location' => $site?->name ?? $mission['location'],
                ]
            );
        }
    }
}
