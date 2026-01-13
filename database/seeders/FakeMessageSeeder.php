<?php

namespace Database\Seeders;

use App\Models\Chat;
use App\Models\Product;
use Illuminate\Database\Seeder;

class FakeMessageSeeder extends Seeder
{
    public function run()
    {
        $products = Product::with('user')->get();

        foreach ($products as $product) {
            // Create 0-5 chat threads per product
            $numChats = rand(0, 5);
            for ($i = 0; $i < $numChats; $i++) {
                $sender = \App\Models\User::where('id', '!=', $product->user_id)
                    ->inRandomOrder()
                    ->first();

                // Create 1-10 messages per chat thread
                $numMessages = rand(1, 10);
                for ($j = 0; $j < $numMessages; $j++) {
                    Chat::create([
                        'product_id' => $product->id,
                        'sender_id' => $j % 2 == 0 ? $sender->id : $product->user_id,
                        'receiver_id' => $j % 2 == 0 ? $product->user_id : $sender->id,
                        'message' => fake()->sentence(),
                        'is_read' => fake()->boolean(),
                        'reported_at' => fake()->boolean(10) ? fake()->dateTimeThisMonth() : null,
                        'report_reason' => fake()->boolean(10) ? fake()->randomElement(['spam', 'offensive', 'scam']) : null
                    ]);
                }
            }
        }
    }
}
