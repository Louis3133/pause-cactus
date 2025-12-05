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
        $categories = ['News', 'Ouah', 'Positif', 'Politique', 'Santé', 'Sport', 'Sciences', 'Monde', 'Environnement', 'Economie', 'Bien-être', 'Positif', 'Numérique', 'Culture', 'Société', 'Animaux', 'Technologie'];

        foreach ($categories as  $category) {
            \App\Models\category::create(['name' => $category]);
        }
    }
}
