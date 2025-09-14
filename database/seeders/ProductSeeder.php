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
        $categories = Category::all();

        // 1. Buat produk untuk setiap kategori
        foreach ($categories as $category) {
            Product::factory()
                ->count(rand(5, 15)) // 5-15 produk per kategori
                ->forCategory($category->id)
                ->create();
        }
    }
}
