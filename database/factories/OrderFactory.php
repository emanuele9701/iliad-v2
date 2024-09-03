<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
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
            'name' => $this->faker->name,
            'total_value' => $this->faker->randomFloat(2, 10, 1000),
            'description' => $this->faker->text,
            'order_date' => $this->faker->date,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Order $order) {
            $products = Product::inRandomOrder()->take(rand(1, 5))->get();
            $totalValue = 0;

            foreach ($products as $product) {
                $qty = rand(1, 10); // quantità casuale tra 1 e 10
                $totalValue += $product->price * $qty;
                $order->products()->attach($product->id, ['qty' => $qty]);
            }

            $order->update(['total_value' => $totalValue]);
        });

    }
}
