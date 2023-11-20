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
            ['name' => 'Home & Kitchen', 'slug' => 'home-kitchen'],
            ['name' => 'Baby' , 'slug' => 'baby'],
            ['name' => 'Electronics' , 'slug' => 'electronics'],
            ['name' => 'Sports & outdoors' , 'slug' => 'sports-n-outdoor'],
            ['name' => 'Pet Supplies ','slug' => 'pet-supplies'],
            ['name' => 'Clothing, Shoes & Jewelry' , 'slug' => 'clothing-shoes-n-jewelry'],
        ]);
    }
}
