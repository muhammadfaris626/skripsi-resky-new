<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics'],
            ['name' => 'Clothing'],
            ['name' => 'Food & Beverage'],
            ['name' => 'Books'],
            ['name' => 'Sports & Outdoors'],
            ['name' => 'Home & Garden'],
            ['name' => 'Toys & Games'],
            ['name' => 'Health & Beauty'],
            ['name' => 'Automotive'],
            ['name' => 'Office Supplies'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
