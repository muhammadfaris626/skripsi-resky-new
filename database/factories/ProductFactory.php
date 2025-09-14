<?php

namespace Database\Factories;

use App\Models\Category;
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
        $purchasePrice = $this->faker->randomFloat(2, 10, 1000);
        $profitMargin = $this->faker->randomFloat(2, 10, 50); // 10% to 50% profit margin
        $sellingPrice = $purchasePrice * (1 + $profitMargin / 100);

        return [
            'category_id' => Category::factory(),
            'product_code' => strtoupper($this->faker->unique()->bothify('PRD-####-???')),
            'product_name' => $this->faker->words(3, true),
            'purchase_price' => $purchasePrice,
            'selling_price' => round($sellingPrice, 2),
            'stock' => $this->faker->numberBetween(0, 100),
        ];
    }

    /**
     * Indicate that the product is out of stock.
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
        ]);
    }

    /**
     * Indicate that the product has low stock.
     */
    public function lowStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => $this->faker->numberBetween(1, 10),
        ]);
    }

    /**
     * Indicate that the product is well stocked.
     */
    public function inStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => $this->faker->numberBetween(50, 200),
        ]);
    }
}
