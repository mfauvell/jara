<?php

use Illuminate\Database\Seeder;
use App\Models\RecipeType;

class RecipeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RecipeType::create([
            'name' => 'Main Dish',
        ]);
        RecipeType::create([
            'name' => 'Dessert',
        ]);
    }
}
