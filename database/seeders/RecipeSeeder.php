<?php
Namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Recipe;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Recipe::create([
            'title' => 'Huevo frito',
            'description' => 'Un delicioso huevo frito',
            'time' => 10,
            'visibility_id' => DB::table('visibilities')->where('name', '=', 'Private')->get(['id'])->first()->id,
            'user_id' => DB::table('users')->where('name', '=', 'User')->get(['id'])->first()->id
        ]);
        DB::table('ingredient_recipe')->insert([
            'ingredient_id' => DB::table('ingredients')->where('name', '=', 'Huevo')->get(['id'])->first()->id,
            'recipe_id' => DB::table('recipes')->where('title', '=', 'Huevo frito')->get(['id'])->first()->id,
            'quantity' => 1,
            'unit' => 'Unidad'
        ]);
        DB::table('ingredient_recipe')->insert([
            'ingredient_id' => DB::table('ingredients')->where('name', '=', 'Aceite')->get(['id'])->first()->id,
            'recipe_id' => DB::table('recipes')->where('title', '=', 'Huevo frito')->get(['id'])->first()->id,
            'quantity' => 1,
            'unit' => 'Chorro'
        ]);
        DB::table('ingredient_recipe')->insert([
            'ingredient_id' => DB::table('ingredients')->where('name', '=', 'Sal')->get(['id'])->first()->id,
            'recipe_id' => DB::table('recipes')->where('title', '=', 'Huevo frito')->get(['id'])->first()->id,
            'quantity' => 1,
            'unit' => 'Pizca'
        ]);
        DB::table('recipe_recipe_type')->insert([
            'recipe_type_id' => DB::table('recipe_types')->where('name', '=', 'Main Dish')->get(['id'])->first()->id,
            'recipe_id' => DB::table('recipes')->where('title', '=', 'Huevo frito')->get(['id'])->first()->id,
        ]);
    }
}
