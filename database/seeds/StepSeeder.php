<?php

use Illuminate\Database\Seeder;
use App\Models\Step;

class StepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Step::create([
            'order' => 1,
            'title' => 'Calentamos el aceite',
            'description' => 'En una sarten, ponemos el aceite a calentar hasta que esté bien caliente',
            'time' => 5,
            'recipe_id' => Illuminate\Support\Facades\DB::table('recipes')->where('title', '=', 'Huevo frito')->get(['id'])->first()->id
        ]);
        Step::create([
            'order' => 2,
            'title' => 'Echamos el huevo cascado',
            'description' => 'Echamos el huevo con al aciete muy caliente, una pizca de sal sobre la yema y, con una espatula, vamos echando aciete caliente sobre esta',
            'time' => 1,
            'recipe_id' => Illuminate\Support\Facades\DB::table('recipes')->where('title', '=', 'Huevo frito')->get(['id'])->first()->id
        ]);
    }
}
