<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VolunteerProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing volunteer users and add profile data
        $volunteers = \App\Models\User::where('role', 'volunteer')->get();
        
        $sampleProfiles = [
            [
                'phone' => '+212 6 12 34 56 78',
                'city' => 'Casablanca',
                'languages' => 'Arabic, English, French',
                'skills' => 'Translation, Customer Service, Event Management',
                'availability' => 'Flexible'
            ],
            [
                'phone' => '+212 6 23 45 67 89',
                'city' => 'Rabat',
                'languages' => 'Arabic, English, Spanish',
                'skills' => 'Medical, First Aid, Emergency Response',
                'availability' => 'Weekends'
            ],
            [
                'phone' => '+212 6 34 56 78 90',
                'city' => 'Marrakech',
                'languages' => 'Arabic, French, Italian',
                'skills' => 'Logistics, Protocol, Hospitality',
                'availability' => 'Flexible'
            ],
            [
                'phone' => '+212 6 45 67 89 01',
                'city' => 'Agadir',
                'languages' => 'Arabic, English, German',
                'skills' => 'IT Support, Technical Assistance, Communication',
                'availability' => 'Evenings'
            ],
            [
                'phone' => '+212 6 56 78 90 12',
                'city' => 'Fès',
                'languages' => 'Arabic, French, English',
                'skills' => 'Translation, Cultural Guide, Public Relations',
                'availability' => 'Weekdays'
            ],
            [
                'phone' => '+212 6 67 89 01 23',
                'city' => 'Tangier',
                'languages' => 'Arabic, English, Spanish, French',
                'skills' => 'Security, Crowd Management, Emergency Response',
                'availability' => 'Flexible'
            ],
            [
                'phone' => '+212 6 78 90 12 34',
                'city' => 'Meknes',
                'languages' => 'Arabic, French',
                'skills' => 'Transportation, Logistics, Driver',
                'availability' => 'Flexible'
            ],
            [
                'phone' => '+212 6 89 01 23 45',
                'city' => 'Oujda',
                'languages' => 'Arabic, English, Arabic',
                'skills' => 'Medical Support, First Aid, Translation',
                'availability' => 'Weekends'
            ]
        ];

        foreach ($volunteers as $index => $volunteer) {
            if (isset($sampleProfiles[$index])) {
                $volunteer->update($sampleProfiles[$index]);
            }
        }
    }
}
