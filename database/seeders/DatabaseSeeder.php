<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create base user with split name fields
        User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'university_email' => 'test.user@university.edu',
            'university_name' => 'Test University',
            'is_email_verified' => true,
            'is_mobile_verified' => true,
            'is_seller_verified' => true,
        ]);

        // Run migrations and seeds in correct order
        $this->call([
            CategorySeeder::class,      // Run this first
            RoleAndPermissionSeeder::class,
            AdminSeeder::class,
            UniversitySeeder::class,
            FakeUserSeeder::class,
            FakeProductSeeder::class,
            FakeMessageSeeder::class,
        ]);
    }
}
