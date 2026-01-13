<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Textbooks',
            'Electronics',
            'Furniture',
            'Study Materials',
            'Room Accessories',
            'Kitchen Appliances',
            'Sports Equipment',
            'Musical Instruments'
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category,
                'slug' => Str::slug($category),
                'description' => 'Category for ' . $category
            ]);
        }
    }
}
