<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LicensesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $licences = ['Copyright (Tous droits réservés)', 'Creative Commons — CC BY', 'Creative Commons — CC BY-SA', 'Creative Commons — CC BY-NC', 'Creative Commons — CC BY-NC-SA', 'Creative Commons — CC BY-ND', 'Creative Commons — CC BY-NC-ND
', 'CC0 (Domaine public)'

];

        foreach ($licences as  $licence) {
            \App\Models\Licence::create(['name' => $licence]);
        }
    }
}
