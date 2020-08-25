<?php

use Illuminate\Database\Seeder;
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
            'visibility_id' => Illuminate\Support\Facades\DB::table('visibilities')->where('name', '=', 'Private')->get(['id'])->first()->id,
            'user_id' => Illuminate\Support\Facades\DB::table('users')->where('name', '=', 'User')->get(['id'])->first()->id
        ]);
        Illuminate\Support\Facades\DB::table('ingredient_recipe')->insert([
            'ingredient_id' => Illuminate\Support\Facades\DB::table('ingredients')->where('name', '=', 'Huevo')->get(['id'])->first()->id,
            'recipe_id' => Illuminate\Support\Facades\DB::table('recipes')->where('title', '=', 'Huevo frito')->get(['id'])->first()->id,
            'quantity' => 1,
            'unit' => 'Unidad'
        ]);
        Illuminate\Support\Facades\DB::table('ingredient_recipe')->insert([
            'ingredient_id' => Illuminate\Support\Facades\DB::table('ingredients')->where('name', '=', 'Aceite')->get(['id'])->first()->id,
            'recipe_id' => Illuminate\Support\Facades\DB::table('recipes')->where('title', '=', 'Huevo frito')->get(['id'])->first()->id,
            'quantity' => 1,
            'unit' => 'Chorro'
        ]);
        Illuminate\Support\Facades\DB::table('ingredient_recipe')->insert([
            'ingredient_id' => Illuminate\Support\Facades\DB::table('ingredients')->where('name', '=', 'Sal')->get(['id'])->first()->id,
            'recipe_id' => Illuminate\Support\Facades\DB::table('recipes')->where('title', '=', 'Huevo frito')->get(['id'])->first()->id,
            'quantity' => 1,
            'unit' => 'Pizca'
        ]);
        Illuminate\Support\Facades\DB::table('recipe_recipe_type')->insert([
            'recipe_type_id' => Illuminate\Support\Facades\DB::table('recipe_types')->where('name', '=', 'Main Dish')->get(['id'])->first()->id,
            'recipe_id' => Illuminate\Support\Facades\DB::table('recipes')->where('title', '=', 'Huevo frito')->get(['id'])->first()->id,
        ]);
    }
}
