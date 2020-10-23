<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        #Permissions User
        Permission::create([
            'key' => 'create',
            'type' => 'create',
            'section' => 'user',
            'description' => 'Create User'
        ]);
        Permission::create([
            'key' => 'edit',
            'type' => 'edit',
            'section' => 'user',
            'description' => 'Edit its own user'
        ]);
        Permission::create([
            'key' => 'editSelf',
            'type' => 'edit',
            'section' => 'user',
            'description' => 'Edit its own user'
        ]);
        Permission::create([
            'key' => 'editOther',
            'type' => 'edit',
            'section' => 'user',
            'description' => 'Edit other user'
        ]);
        Permission::create([
            'key' => 'delete',
            'type' => 'delete',
            'section' => 'user',
            'description' => 'Delete its own user'
        ]);
        Permission::create([
            'key' => 'deleteSelf',
            'type' => 'delete',
            'section' => 'user',
            'description' => 'Delete its own user'
        ]);
        Permission::create([
            'key' => 'deleteOther',
            'type' => 'delete',
            'section' => 'user',
            'description' => 'Delete other User'
        ]);
        Permission::create([
            'key' => 'view',
            'type' => 'view',
            'section' => 'user',
            'description' => 'View own user'
        ]);
        Permission::create([
            'key' => 'viewSelf',
            'type' => 'view',
            'section' => 'user',
            'description' => 'View own user'
        ]);
        Permission::create([
            'key' => 'viewOther',
            'type' => 'view',
            'section' => 'user',
            'description' => 'View other users'
        ]);
        Permission::create([
            'key' => 'assignPermission',
            'type' => 'edit',
            'section' => 'user',
            'description' => 'Assig permission its own user'
        ]);
        #Permissions Ingredient
        Permission::create([
            'key' => 'create',
            'type' => 'create',
            'section' => 'ingredient',
            'description' => 'Create ingredients'
        ]);
        Permission::create([
            'key' => 'edit',
            'type' => 'edit',
            'section' => 'ingredient',
            'description' => 'Edit ingredients'
        ]);
        Permission::create([
            'key' => 'delete',
            'type' => 'delete',
            'section' => 'ingredient',
            'description' => 'Delete ingredients'
        ]);
        Permission::create([
            'key' => 'view',
            'type' => 'view',
            'section' => 'ingredient',
            'description' => 'View ingredients'
        ]);
        Permission::create([
            'key' => 'uploadImage',
            'type' => 'edit',
            'section' => 'ingredient',
            'description' => 'Upload images to ingredients'
        ]);
        #Permissions Recipe
        Permission::create([
            'key' => 'create',
            'type' => 'create',
            'section' => 'recipe',
            'description' => 'Create recipes'
        ]);
        Permission::create([
            'key' => 'edit',
            'type' => 'edit',
            'section' => 'recipe',
            'description' => 'Edit mine recipes'
        ]);
        Permission::create([
            'key' => 'editMine',
            'type' => 'edit',
            'section' => 'recipe',
            'description' => 'Edit recipes'
        ]);
        Permission::create([
            'key' => 'editPublic',
            'type' => 'edit',
            'section' => 'recipe',
            'description' => 'Edit public recipes'
        ]);
        Permission::create([
            'key' => 'editOther',
            'type' => 'edit',
            'section' => 'recipe',
            'description' => 'Edit other user recipes'
        ]);
        Permission::create([
            'key' => 'delete',
            'type' => 'delete',
            'section' => 'recipe',
            'description' => 'Delete recipes'
        ]);
        Permission::create([
            'key' => 'deleteMine',
            'type' => 'delete',
            'section' => 'recipe',
            'description' => 'Delete mine recipes'
        ]);
        Permission::create([
            'key' => 'deletePublic',
            'type' => 'delete',
            'section' => 'recipe',
            'description' => 'Delete public recipes'
        ]);
        Permission::create([
            'key' => 'deleteOther',
            'type' => 'delete',
            'section' => 'recipe',
            'description' => 'Delete other user recipes'
        ]);
        Permission::create([
            'key' => 'view',
            'type' => 'view',
            'section' => 'recipe',
            'description' => 'View mine recipes'
        ]);
        Permission::create([
            'key' => 'viewMine',
            'type' => 'view',
            'section' => 'recipe',
            'description' => 'View mine recipes'
        ]);
        Permission::create([
            'key' => 'viewPublic',
            'type' => 'view',
            'section' => 'recipe',
            'description' => 'View public recipes'
        ]);
        Permission::create([
            'key' => 'viewOther',
            'type' => 'view',
            'section' => 'recipe',
            'description' => 'View other user recipes'
        ]);
        Permission::create([
            'key' => 'uploadImage',
            'type' => 'edit',
            'section' => 'recipe',
            'description' => 'Upload images to recipes'
        ]);
        DB::table('permission_role')->insert([
            'role_id' => DB::table('roles')->where('name', '=', 'SuperAdmin')->get(['id'])->first()->id,
            'permission_id' => DB::table('permissions')->where([['key', '=', 'create'],['type','=','create'],['section','=','user']])->get(['id'])->first()->id,
        ]);
        DB::table('permission_role')->insert([
            'role_id' => DB::table('roles')->where('name', '=', 'SuperAdmin')->get(['id'])->first()->id,
            'permission_id' => DB::table('permissions')->where([['key', '=', 'edit'],['type','=','edit'],['section','=','user']])->get(['id'])->first()->id,
        ]);
        DB::table('permission_role')->insert([
            'role_id' => DB::table('roles')->where('name', '=', 'SuperAdmin')->get(['id'])->first()->id,
            'permission_id' => DB::table('permissions')->where([['key', '=', 'editSelf'],['type','=','edit'],['section','=','user']])->get(['id'])->first()->id,
        ]);
        DB::table('permission_role')->insert([
            'role_id' => DB::table('roles')->where('name', '=', 'SuperAdmin')->get(['id'])->first()->id,
            'permission_id' => DB::table('permissions')->where([['key', '=', 'editOther'],['type','=','edit'],['section','=','user']])->get(['id'])->first()->id,
        ]);
        DB::table('permission_role')->insert([
            'role_id' => DB::table('roles')->where('name', '=', 'SuperAdmin')->get(['id'])->first()->id,
            'permission_id' => DB::table('permissions')->where([['key', '=', 'delete'],['type','=','delete'],['section','=','user']])->get(['id'])->first()->id,
        ]);
        DB::table('permission_role')->insert([
            'role_id' => DB::table('roles')->where('name', '=', 'SuperAdmin')->get(['id'])->first()->id,
            'permission_id' => DB::table('permissions')->where([['key', '=', 'deleteSelf'],['type','=','delete'],['section','=','user']])->get(['id'])->first()->id,
        ]);
        DB::table('permission_role')->insert([
            'role_id' => DB::table('roles')->where('name', '=', 'SuperAdmin')->get(['id'])->first()->id,
            'permission_id' => DB::table('permissions')->where([['key', '=', 'deleteOther'],['type','=','delete'],['section','=','user']])->get(['id'])->first()->id,
        ]);
        DB::table('permission_role')->insert([
            'role_id' => DB::table('roles')->where('name', '=', 'SuperAdmin')->get(['id'])->first()->id,
            'permission_id' => DB::table('permissions')->where([['key', '=', 'view'],['type','=','view'],['section','=','user']])->get(['id'])->first()->id,
        ]);
        DB::table('permission_role')->insert([
            'role_id' => DB::table('roles')->where('name', '=', 'SuperAdmin')->get(['id'])->first()->id,
            'permission_id' => DB::table('permissions')->where([['key', '=', 'viewSelf'],['type','=','view'],['section','=','user']])->get(['id'])->first()->id,
        ]);
        DB::table('permission_role')->insert([
            'role_id' => DB::table('roles')->where('name', '=', 'SuperAdmin')->get(['id'])->first()->id,
            'permission_id' => DB::table('permissions')->where([['key', '=', 'viewOther'],['type','=','view'],['section','=','user']])->get(['id'])->first()->id,
        ]);
        DB::table('permission_role')->insert([
            'role_id' => DB::table('roles')->where('name', '=', 'SuperAdmin')->get(['id'])->first()->id,
            'permission_id' => DB::table('permissions')->where([['key', '=', 'assignPermission'],['type','=','edit'],['section','=','user']])->get(['id'])->first()->id,
        ]);
        DB::table('permission_role')->insert([
            'role_id' => DB::table('roles')->where('name', '=', 'SuperAdmin')->get(['id'])->first()->id,
            'permission_id' => DB::table('permissions')->where([['key', '=', 'create'],['type','=','create'],['section','=','ingredient']])->get(['id'])->first()->id,
        ]);
        DB::table('permission_role')->insert([
            'role_id' => DB::table('roles')->where('name', '=', 'SuperAdmin')->get(['id'])->first()->id,
            'permission_id' => DB::table('permissions')->where([['key', '=', 'edit'],['type','=','edit'],['section','=','ingredient']])->get(['id'])->first()->id,
        ]);
        DB::table('permission_role')->insert([
            'role_id' => DB::table('roles')->where('name', '=', 'SuperAdmin')->get(['id'])->first()->id,
            'permission_id' => DB::table('permissions')->where([['key', '=', 'delete'],['type','=','delete'],['section','=','ingredient']])->get(['id'])->first()->id,
        ]);
        DB::table('permission_role')->insert([
            'role_id' => DB::table('roles')->where('name', '=', 'SuperAdmin')->get(['id'])->first()->id,
            'permission_id' => DB::table('permissions')->where([['key', '=', 'view'],['type','=','view'],['section','=','ingredient']])->get(['id'])->first()->id,
        ]);
        DB::table('permission_role')->insert([
            'role_id' => DB::table('roles')->where('name', '=', 'SuperAdmin')->get(['id'])->first()->id,
            'permission_id' => DB::table('permissions')->where([['key', '=', 'uploadImage'],['type','=','edit'],['section','=','ingredient']])->get(['id'])->first()->id,
        ]);
        DB::table('permission_role')->insert([
            'role_id' => DB::table('roles')->where('name', '=', 'SuperAdmin')->get(['id'])->first()->id,
            'permission_id' => DB::table('permissions')->where([['key', '=', 'create'],['type','=','create'],['section','=','recipe']])->get(['id'])->first()->id,
        ]);
        DB::table('permission_role')->insert([
            'role_id' => DB::table('roles')->where('name', '=', 'SuperAdmin')->get(['id'])->first()->id,
            'permission_id' => DB::table('permissions')->where([['key', '=', 'edit'],['type','=','edit'],['section','=','recipe']])->get(['id'])->first()->id,
        ]);
        DB::table('permission_role')->insert([
            'role_id' => DB::table('roles')->where('name', '=', 'SuperAdmin')->get(['id'])->first()->id,
            'permission_id' => DB::table('permissions')->where([['key', '=', 'editMine'],['type','=','edit'],['section','=','recipe']])->get(['id'])->first()->id,
        ]);
        DB::table('permission_role')->insert([
            'role_id' => DB::table('roles')->where('name', '=', 'SuperAdmin')->get(['id'])->first()->id,
            'permission_id' => DB::table('permissions')->where([['key', '=', 'editPublic'],['type','=','edit'],['section','=','recipe']])->get(['id'])->first()->id,
        ]);
        DB::table('permission_role')->insert([
            'role_id' => DB::table('roles')->where('name', '=', 'SuperAdmin')->get(['id'])->first()->id,
            'permission_id' => DB::table('permissions')->where([['key', '=', 'editOther'],['type','=','edit'],['section','=','recipe']])->get(['id'])->first()->id,
        ]);
        DB::table('permission_role')->insert([
            'role_id' => DB::table('roles')->where('name', '=', 'SuperAdmin')->get(['id'])->first()->id,
            'permission_id' => DB::table('permissions')->where([['key', '=', 'delete'],['type','=','delete'],['section','=','recipe']])->get(['id'])->first()->id,
        ]);
        DB::table('permission_role')->insert([
            'role_id' => DB::table('roles')->where('name', '=', 'SuperAdmin')->get(['id'])->first()->id,
            'permission_id' => DB::table('permissions')->where([['key', '=', 'deleteMine'],['type','=','delete'],['section','=','recipe']])->get(['id'])->first()->id,
        ]);
        DB::table('permission_role')->insert([
            'role_id' => DB::table('roles')->where('name', '=', 'SuperAdmin')->get(['id'])->first()->id,
            'permission_id' => DB::table('permissions')->where([['key', '=', 'deletePublic'],['type','=','delete'],['section','=','recipe']])->get(['id'])->first()->id,
        ]);
        DB::table('permission_role')->insert([
            'role_id' => DB::table('roles')->where('name', '=', 'SuperAdmin')->get(['id'])->first()->id,
            'permission_id' => DB::table('permissions')->where([['key', '=', 'deleteOther'],['type','=','delete'],['section','=','recipe']])->get(['id'])->first()->id,
        ]);
        DB::table('permission_role')->insert([
            'role_id' => DB::table('roles')->where('name', '=', 'SuperAdmin')->get(['id'])->first()->id,
            'permission_id' => DB::table('permissions')->where([['key', '=', 'view'],['type','=','view'],['section','=','recipe']])->get(['id'])->first()->id,
        ]);
        DB::table('permission_role')->insert([
            'role_id' => DB::table('roles')->where('name', '=', 'SuperAdmin')->get(['id'])->first()->id,
            'permission_id' => DB::table('permissions')->where([['key', '=', 'viewMine'],['type','=','view'],['section','=','recipe']])->get(['id'])->first()->id,
        ]);
        DB::table('permission_role')->insert([
            'role_id' => DB::table('roles')->where('name', '=', 'SuperAdmin')->get(['id'])->first()->id,
            'permission_id' => DB::table('permissions')->where([['key', '=', 'viewPublic'],['type','=','view'],['section','=','recipe']])->get(['id'])->first()->id,
        ]);
        DB::table('permission_role')->insert([
            'role_id' => DB::table('roles')->where('name', '=', 'SuperAdmin')->get(['id'])->first()->id,
            'permission_id' => DB::table('permissions')->where([['key', '=', 'viewOther'],['type','=','view'],['section','=','recipe']])->get(['id'])->first()->id,
        ]);
        DB::table('permission_role')->insert([
            'role_id' => DB::table('roles')->where('name', '=', 'SuperAdmin')->get(['id'])->first()->id,
            'permission_id' => DB::table('permissions')->where([['key', '=', 'uploadImage'],['type','=','edit'],['section','=','recipe']])->get(['id'])->first()->id,
        ]);
    }
}
