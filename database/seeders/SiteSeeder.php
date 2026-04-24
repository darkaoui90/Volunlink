<?php

namespace Database\Seeders;

use App\Models\Site;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    public function run(): void
    {
        $sites = [
            [
                'name' => 'Casablanca Stadium',
                'city' => 'Casablanca',
                'type' => 'Stadium',
                'address' => 'Boulevard de la Resistance, Casablanca',
                'capacity' => 45000,
                'description' => 'Main stadium used for entry support and large match-day operations.',
                'latitude' => 33.5731100,
                'longitude' => -7.5898400,
            ],
            [
                'name' => 'Rabat Media Center',
                'city' => 'Rabat',
                'type' => 'Media Center',
                'address' => 'Zone Diplomatique, Rabat',
                'capacity' => 500,
                'description' => 'Media accreditation and press coordination center.',
                'latitude' => 34.0208800,
                'longitude' => -6.8416500,
            ],
            [
                'name' => 'Marrakech Shuttle Hub',
                'city' => 'Marrakech',
                'type' => 'Transport Hub',
                'address' => 'Avenue Mohammed VI, Marrakech',
                'capacity' => 250,
                'description' => 'Volunteer transport coordination and shuttle staging area.',
                'latitude' => 31.6294700,
                'longitude' => -7.9810800,
            ],
            [
                'name' => 'Agadir Fan Zone',
                'city' => 'Agadir',
                'type' => 'Fan Zone',
                'address' => 'Corniche, Agadir',
                'capacity' => 15000,
                'description' => 'Public-facing fan hospitality and event support area.',
                'latitude' => 30.4201800,
                'longitude' => -9.5981500,
            ],
            [
                'name' => 'Tangier Stadium',
                'city' => 'Tangier',
                'type' => 'Stadium',
                'address' => 'Route de Tetouan, Tangier',
                'capacity' => 38000,
                'description' => 'Northern stadium site with crowd and medical support operations.',
                'latitude' => 35.7594700,
                'longitude' => -5.8339500,
            ],
            [
                'name' => 'Casablanca Operations Center',
                'city' => 'Casablanca',
                'type' => 'Operations Center',
                'address' => 'Sidi Maarouf, Casablanca',
                'capacity' => 800,
                'description' => 'Back-office planning center for logistics, signage, and equipment.',
                'latitude' => 33.5318000,
                'longitude' => -7.6418300,
            ],
        ];

        foreach ($sites as $site) {
            Site::updateOrCreate(
                ['name' => $site['name']],
                $site
            );
        }
    }
}
