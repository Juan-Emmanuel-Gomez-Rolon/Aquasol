<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sale>
 */
class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::inRandomOrder()->first()->id,
            'total' => $this->faker->randomFloat(2, 1, 1000),
            'client_name' => $this->faker->name,
            'client_email' => $this->faker->email,
            'client_phone' => $this->faker->phoneNumber,
            'client_address' => $this->faker->address
        ];
    }
}
