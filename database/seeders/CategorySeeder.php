<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $categories = ['News', 'Ouah', 'Positif'];

        foreach ($categories as  $category) {
            \App\Models\category::create(['name' => $category]);
        }
    }
}
