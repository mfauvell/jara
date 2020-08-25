<?php

use Illuminate\Database\Seeder;
use App\Models\Visibility;

class VisibilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Visibility::create([
            'name' => 'Public'
        ]);
        Visibility::create([
            'name' => 'Private'
        ]);
    }
}
