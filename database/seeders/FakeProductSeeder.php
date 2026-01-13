<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;

class FakeProductSeeder extends Seeder
{
    public function run()
    {
        $users = User::where('is_seller_verified', true)->get();
        $categories = Category::all();
        $conditions = ['New', 'Like New', 'Good', 'Fair', 'Poor'];
        $statuses = ['pending', 'approved', 'rejected', 'flagged'];

        // Placeholder images
        $images = [
            'https://via.placeholder.com/500x400?text=Product+Image+1',
            'https://via.placeholder.com/500x400?text=Product+Image+2',
            'https://via.placeholder.com/500x400?text=Product+Image+3',
            'https://via.placeholder.com/500x400?text=Product+Image+4',
            'https://via.placeholder.com/500x400?text=Product+Image+5',
            'https://via.placeholder.com/500x400?text=Electronics+Item',
            'https://via.placeholder.com/500x400?text=Furniture+Item',
            'https://via.placeholder.com/500x400?text=Study+Material',
            'https://via.placeholder.com/500x400?text=Room+Accessory',
            'https://via.placeholder.com/500x400?text=Kitchen+Appliance'
        ];

        // Alternatively use Lorem Picsum for more realistic images
        $realImages = [
            'https://picsum.photos/id/1/500/400',
            'https://picsum.photos/id/20/500/400',
            'https://picsum.photos/id/45/500/400',
            'https://picsum.photos/id/49/500/400',
            'https://picsum.photos/id/96/500/400',
            'https://picsum.photos/id/237/500/400',
            'https://picsum.photos/id/334/500/400',
            'https://picsum.photos/id/331/500/400',
            'https://picsum.photos/id/180/500/400',
            'https://picsum.photos/id/169/500/400'
        ];

        foreach ($users as $user) {
            $numProducts = rand(1, 5);
            for ($i = 0; $i < $numProducts; $i++) {
                $mrp = fake()->numberBetween(10, 1000);
                $discount = fake()->numberBetween(0, 30);
                $selling_price = $mrp * (1 - ($discount/100));
                
                // Generate 1-4 random images for each product
                $productImages = [];
                $numImages = rand(1, 4);
                for ($j = 0; $j < $numImages; $j++) {
                    // Use real images or placeholders
                    $useRealImages = fake()->boolean(70);
                    $productImages[] = $useRealImages 
                        ? $realImages[array_rand($realImages)]
                        : $images[array_rand($images)];
                }

                Product::create([
                    'user_id' => $user->id,
                    'category_id' => $categories->random()->id,
                    'name' => fake()->words(3, true),
                    'description' => fake()->paragraph(),
                    'mrp' => $mrp,
                    'selling_price' => $selling_price,
                    'price' => $selling_price,
                    'condition' => fake()->randomElement($conditions),
                    'status' => fake()->randomElement($statuses),
                    'views_count' => fake()->numberBetween(0, 100),
                    'last_viewed_at' => fake()->dateTimeThisMonth(),
                    'reports_count' => fake()->numberBetween(0, 5),
                    'photos' => json_encode($productImages)
                ]);
            }
        }
    }
}
