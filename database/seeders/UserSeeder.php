<?php
Namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'SuperAdmin',
            'email' => 'superadmin@test.com',
            'password' => bcrypt('Uned123'),
            'role_id' => DB::table('roles')->where('name', '=', 'SuperAdmin')->get(['id'])->first()->id
        ]);
        User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('Uned123'),
            'role_id' => DB::table('roles')->where('name', '=', 'Admin')->get(['id'])->first()->id
        ]);
        User::create([
            'name' => 'User',
            'email' => 'user@test.com',
            'password' => bcrypt('Uned123'),
            'role_id' => DB::table('roles')->where('name', '=', 'User')->get(['id'])->first()->id
        ]);
    }
}
