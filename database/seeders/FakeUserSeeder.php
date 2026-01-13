<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FakeUserSeeder extends Seeder
{
    public function run()
    {
        // Create 20 verified students
        User::factory()->count(20)->state(function (array $attributes) {
            return [
                'is_email_verified' => true,
                'is_mobile_verified' => true,
                'university_name' => fake()->randomElement(['State University', 'Tech Institute', 'City College', 'Liberal Arts University']),
                'university_email' => 'student.' . Str::random(8) . '@' . fake()->randomElement(['state.edu', 'tech.edu', 'city.edu', 'liberal.edu']),
                'user_type' => 'both',
                'is_seller_verified' => true,
                'safety_rating' => fake()->numberBetween(0, 5),
                'successful_transactions' => fake()->numberBetween(0, 15)
            ];
        })->create();
    }
}
