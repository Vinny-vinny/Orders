<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_number' => 'ORN-' . str_pad($this->faker->unique()->randomNumber(5), 5, '0', STR_PAD_LEFT),
            'user_id'=> User::inRandomOrder()->first()->id,
            'product_id'=> Product::inRandomOrder()->first()->id,
            'amount' => $this->faker->randomFloat(2, 1, 100),
            'quantity' => $this->faker->numberBetween(1, 10),
        ];
    }
}
