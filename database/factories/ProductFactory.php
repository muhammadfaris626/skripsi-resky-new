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
        $purchasePrice = $this->faker->randomFloat(2, 10000, 1000000); // 10rb - 1jt
        $profitMargin = $this->faker->randomFloat(2, 1.2, 3.0); // 20% - 200% profit
        $sellingPrice = $purchasePrice * $profitMargin;

        return [
            'category_id' => Category::inRandomOrder()->first()->id,
            'product_code' => $this->generateProductCode(),
            'product_name' => $this->faker->words(3, true) . ' ' . $this->faker->randomElement(['Pro', 'Max', 'Plus', 'Premium', 'Standard']),
            'purchase_price' => $purchasePrice,
            'selling_price' => $sellingPrice,
            'stock' => $this->faker->numberBetween(0, 500),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    private function generateProductCode(): string
    {
        $prefix = $this->faker->randomElement(['PRD', 'ITM', 'SKU', 'BRG']);
        $number = $this->faker->unique()->numberBetween(10000, 99999);
        return $prefix . '-' . $number;
    }

    // State untuk kategori tertentu
    public function forCategory($categoryId): static
    {
        return $this->state(fn (array $attributes) => [
            'category_id' => $categoryId,
        ]);
    }

    // State untuk produk dengan stok kosong
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
        ]);
    }

    // State untuk produk dengan stok rendah
    public function lowStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => $this->faker->numberBetween(1, 10),
        ]);
    }

    // State untuk produk dengan stok tinggi
    public function highStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => $this->faker->numberBetween(100, 1000),
        ]);
    }

    // State untuk produk murah
    public function cheap(): static
    {
        $purchasePrice = $this->faker->randomFloat(2, 5000, 50000);
        $sellingPrice = $purchasePrice * $this->faker->randomFloat(2, 1.2, 2.0);

        return $this->state(fn (array $attributes) => [
            'purchase_price' => $purchasePrice,
            'selling_price' => $sellingPrice,
        ]);
    }

    // State untuk produk mahal
    public function expensive(): static
    {
        $purchasePrice = $this->faker->randomFloat(2, 500000, 5000000);
        $sellingPrice = $purchasePrice * $this->faker->randomFloat(2, 1.3, 2.5);

        return $this->state(fn (array $attributes) => [
            'purchase_price' => $purchasePrice,
            'selling_price' => $sellingPrice,
        ]);
    }

    // State untuk kode produk custom
    public function withCode(string $code): static
    {
        return $this->state(fn (array $attributes) => [
            'product_code' => $code,
        ]);
    }

    // State untuk nama produk custom
    public function withName(string $name): static
    {
        return $this->state(fn (array $attributes) => [
            'product_name' => $name,
        ]);
    }
}
