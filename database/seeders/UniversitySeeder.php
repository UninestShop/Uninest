<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UniversitySeeder extends Seeder
{
    public function run()
    {
        $universities = [
            ['name' => 'State University', 'domain' => 'state.edu', 'location' => json_encode(['lat' => 40.7128, 'lng' => -74.0060])],
            ['name' => 'Tech Institute', 'domain' => 'tech.edu', 'location' => json_encode(['lat' => 34.0522, 'lng' => -118.2437])],
            ['name' => 'City College', 'domain' => 'city.edu', 'location' => json_encode(['lat' => 41.8781, 'lng' => -87.6298])],
            ['name' => 'Liberal Arts University', 'domain' => 'liberal.edu', 'location' => json_encode(['lat' => 42.3601, 'lng' => -71.0589])],
        ];

        foreach ($universities as $university) {
            DB::table('universities')->insert($university);
        }
    }
}
