<?php
Namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'SuperAdmin'
        ]);
        Role::create([
            'name' => 'Admin'
        ]);
        Role::create([
            'name' => 'User'
        ]);
    }
}
