<?php

use Illuminate\Database\Seeder;
use App\Models\Ingredient;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Ingredient::create([
            'name' => 'Huevo'
        ]);
        Ingredient::create([
            'name' => 'Aceite'
        ]);
        Ingredient::create([
            'name' => 'Sal'
        ]);
    }
}
