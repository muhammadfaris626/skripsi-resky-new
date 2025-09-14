<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            'Electronics' => 'Electronic devices and accessories',
            'Clothing' => 'Apparel and fashion items',
            'Food & Beverage' => 'Food products and drinks',
            'Books' => 'Books and publications',
            'Sports & Outdoors' => 'Sports equipment and outdoor gear',
            'Home & Garden' => 'Home improvement and garden supplies',
            'Toys & Games' => 'Toys and gaming products',
            'Health & Beauty' => 'Health and beauty products',
            'Automotive' => 'Auto parts and accessories',
            'Office Supplies' => 'Office and stationery items'
        ];

        $name = $this->faker->unique()->randomElement(array_keys($categories));

        return [
            'name' => $name,
        ];
    }
}
