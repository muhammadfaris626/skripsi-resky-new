<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing products to avoid duplicates

        // Get all categories
        $categories = Category::all();

        if ($categories->isEmpty()) {
            $this->command->warn('No categories found. Please run CategorySeeder first.');
            return;
        }

        // Specific products for each category
        $specificProducts = [
            'Electronics' => [
                ['name' => 'Laptop Gaming ASUS ROG', 'code' => 'ELEC-001', 'purchase' => 1200, 'selling' => 1500, 'stock' => 15],
                ['name' => 'iPhone 15 Pro Max', 'code' => 'ELEC-002', 'purchase' => 1000, 'selling' => 1300, 'stock' => 25],
                ['name' => 'Samsung Smart TV 55"', 'code' => 'ELEC-003', 'purchase' => 600, 'selling' => 850, 'stock' => 8],
                ['name' => 'Sony WH-1000XM5 Headphones', 'code' => 'ELEC-004', 'purchase' => 250, 'selling' => 380, 'stock' => 0],
                ['name' => 'iPad Air 5th Gen', 'code' => 'ELEC-005', 'purchase' => 500, 'selling' => 650, 'stock' => 12],
            ],
            'Clothing' => [
                ['name' => 'Nike Air Max 270', 'code' => 'CLTH-001', 'purchase' => 80, 'selling' => 150, 'stock' => 30],
                ['name' => 'Adidas Hoodie Original', 'code' => 'CLTH-002', 'purchase' => 40, 'selling' => 75, 'stock' => 45],
                ['name' => 'Levis 501 Original Jeans', 'code' => 'CLTH-003', 'purchase' => 50, 'selling' => 90, 'stock' => 5],
                ['name' => 'Polo Ralph Lauren Shirt', 'code' => 'CLTH-004', 'purchase' => 45, 'selling' => 85, 'stock' => 20],
                ['name' => 'Under Armour Sports Jacket', 'code' => 'CLTH-005', 'purchase' => 60, 'selling' => 110, 'stock' => 0],
            ],
            'Food & Beverage' => [
                ['name' => 'Organic Green Tea Pack', 'code' => 'FOOD-001', 'purchase' => 8, 'selling' => 15, 'stock' => 100],
                ['name' => 'Premium Coffee Beans 1kg', 'code' => 'FOOD-002', 'purchase' => 15, 'selling' => 28, 'stock' => 60],
                ['name' => 'Chocolate Gift Box', 'code' => 'FOOD-003', 'purchase' => 12, 'selling' => 25, 'stock' => 35],
                ['name' => 'Protein Bar Pack (12pcs)', 'code' => 'FOOD-004', 'purchase' => 20, 'selling' => 35, 'stock' => 8],
                ['name' => 'Mineral Water 24 Bottles', 'code' => 'FOOD-005', 'purchase' => 5, 'selling' => 12, 'stock' => 200],
            ],
        ];

        $productCounter = 1;

        // Create specific products for categories that have them defined
        foreach ($specificProducts as $categoryName => $products) {
            $category = $categories->where('name', $categoryName)->first();
            if ($category) {
                foreach ($products as $product) {
                    Product::create([
                        'category_id' => $category->id,
                        'product_code' => $product['code'],
                        'product_name' => $product['name'],
                        'purchase_price' => $product['purchase'],
                        'selling_price' => $product['selling'],
                        'stock' => $product['stock'],
                    ]);
                    $productCounter++;
                }
            }
        }

        // Generate products for remaining categories with unique codes
        $remainingCategories = $categories->whereNotIn('name', array_keys($specificProducts));

        foreach ($remainingCategories as $category) {
            // Create 5-8 products per category (reduced to avoid uniqueness issues)
            $productCount = rand(5, 8);

            for ($i = 1; $i <= $productCount; $i++) {
                Product::create([
                    'category_id' => $category->id,
                    'product_code' => 'PRD-' . str_pad($productCounter, 4, '0', STR_PAD_LEFT),
                    'product_name' => $this->generateProductName($category->name, $i),
                    'purchase_price' => rand(10, 1000),
                    'selling_price' => rand(15, 1500),
                    'stock' => rand(0, 100),
                ]);
                $productCounter++;
            }
        }

        // Create additional products with various stock levels using manual creation
        $stockVariations = [
            ['min_stock' => 10, 'max_stock' => 100, 'count' => 10], // In stock
            ['min_stock' => 1, 'max_stock' => 9, 'count' => 5],    // Low stock
            ['min_stock' => 0, 'max_stock' => 0, 'count' => 3],    // Out of stock
        ];

        foreach ($stockVariations as $variation) {
            for ($i = 1; $i <= $variation['count']; $i++) {
                $randomCategory = $categories->random();
                Product::create([
                    'category_id' => $randomCategory->id,
                    'product_code' => 'PRD-' . str_pad($productCounter, 4, '0', STR_PAD_LEFT),
                    'product_name' => $this->generateProductName($randomCategory->name, $i, 'Extra'),
                    'purchase_price' => rand(10, 500),
                    'selling_price' => rand(15, 750),
                    'stock' => rand($variation['min_stock'], $variation['max_stock']),
                ]);
                $productCounter++;
            }
        }

        $this->command->info('Products seeded successfully!');
    }

    /**
     * Generate product name based on category
     */
    private function generateProductName($categoryName, $index, $prefix = '')
    {
        $productTypes = [
            'Electronics' => ['Smartphone', 'Tablet', 'Laptop', 'Camera', 'Speaker', 'Monitor', 'Keyboard', 'Mouse'],
            'Clothing' => ['T-Shirt', 'Jeans', 'Jacket', 'Dress', 'Shoes', 'Hat', 'Sweater', 'Shorts'],
            'Food & Beverage' => ['Snack', 'Drink', 'Coffee', 'Tea', 'Juice', 'Cookie', 'Cake', 'Bread'],
            'Books' => ['Novel', 'Textbook', 'Magazine', 'Comic', 'Dictionary', 'Biography', 'Guide', 'Manual'],
            'Sports' => ['Ball', 'Racket', 'Shoes', 'Jersey', 'Equipment', 'Gear', 'Accessory', 'Tool'],
        ];

        $brands = ['Premium', 'Classic', 'Modern', 'Elite', 'Pro', 'Standard', 'Deluxe', 'Special'];

        // Get product types for category or use generic ones
        $types = $productTypes[$categoryName] ?? ['Product', 'Item', 'Goods', 'Material', 'Equipment'];
        $type = $types[array_rand($types)];
        $brand = $brands[array_rand($brands)];

        return trim($prefix . ' ' . $brand . ' ' . $type . ' ' . $index);
    }
}
