<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        City::insert([
            ['name' => 'Bhavnagar'],
            ['name' => 'Ahmadabad'],
            ['name' => 'Rajkot'],
            ['name' => 'Jamnagar'],
            ['name'=>'Surat'],
        ]);
    }
}
