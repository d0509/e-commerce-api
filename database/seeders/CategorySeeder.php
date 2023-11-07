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
        Category::insert([
            ['name' => 'Home & Kitchen'],
            ['name' => 'Baby'],
            ['name' => 'Electronics'],
            ['name' => 'Sports & outdoors'],
            ['name' => 'Pet Supplies '],
            ['name' => 'Clothing, Shoes & Jewelry'],
        ]);
    }
}
