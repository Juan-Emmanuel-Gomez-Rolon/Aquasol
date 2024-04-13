<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Sale;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'admin',
        ]);

        // \App\Models\User::factory(10)->create();

        // \App\Models\Product::factory(50)->create();

        // // 50 entries
        // for ($i = 0; $i < 50; $i++) {
        //     $entry = \App\Models\Entry::factory()->create();
        //     $products = \App\Models\Product::inRandomOrder()->limit(rand(1, 10))->get();
        //     foreach ($products as $product) {
        //         $qty = rand(1, 100);
        //         $entry->products()->attach($product->id, ['quantity' => $qty]);

        //         $product->stock += $qty;
        //         $product->save();
        //     }
        // }

        // // 15 sales
        // for ($i = 0; $i < 15; $i++) {
        //     $sale = \App\Models\Sale::factory()->create();
        //     $products = \App\Models\Product::inRandomOrder()->limit(rand(1, 10))->get();

        //     foreach ($products as $product) {
        //         $qty = rand(1, 100);

        //         $price = $product->price; // or any other way to determine the price
        //         $sale->products()->attach($product->id, ['price' => $price, 'quantity' => $qty]);
        //     }

        //     // Re-fetch the sale from the database
        //     $sale = Sale::find($sale->id);

        //     // Calculate the total
        //     $total = $sale->products->sum(function ($product) {
        //         return $product->pivot->price * $product->pivot->quantity;
        //     });

        //     $sale->total = $total;
        //     $sale->save();
        // }
    }
}
