<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->text,
            'barcode' => $this->faker->ean13,
            'price' => $this->faker->randomFloat(2, 1, 1000),
            'stock' => 0,
            'image' => $this->faker->imageUrl(),
            'last_sale' => null,
            'last_entry' => null
        ];
    }
}
