<?php
Namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ProductionSeeder::class,
            UserSeeder::class,
            IngredientSeeder::class,
            RecipeSeeder::class,
            StepSeeder::class,
        ]);
    }
}
