<?php
namespace App\Models;

use Illuminate\Support\Facades\DB;
use phpseclib\Net\SCP;

class Police
{

    /**
     * Check if a user can make a action in a entity
     *
     * @param String $entity
     * @param String $action
     * @param User $user
     * @param mixed $about
     * @return boolean
     */
    public function can_do(String $entity, String $action, User $user, $about = null) {
        $return = false;
        switch ($entity) {
            case 'user':
                $return = $this->can_do_user($action,$user,$about);
                break;

            case 'ingredient':
                $return = $this->can_do_ingredient($action,$user,$about);
                break;

            case 'recipe':
                $return = $this->can_do_recipe($action,$user,$about);
                break;

            default:
                #TODO: Should we permit not related entity permission?
                $return = false;
                break;
        }

        return $return;
    }

    private function can_do_user(String $action, User $user, User $user_check = null){
        $return = false;
        $permission = $this->get_details_permission($action, 'user');
        switch ($permission->type) {
            case 'view':
                $related_permissions = $this->get_permissions_type_section('view','user');
                $return = $this->check_permission($user->role_id,$related_permissions['view']->id);
                if ($return && isset($user_check)){
                    if ($user->id == $user_check->id) {
                        $return = $this->check_permission($user->role_id,$related_permissions['viewSelf']->id);
                    } else {
                        $return = $this->check_permission($user->role_id,$related_permissions['viewOther']->id);
                    }
                }
                break;

            case 'edit':
                $related_permissions = $this->get_permissions_type_section('edit','user');
                $return = $this->check_permission($user->role_id,$related_permissions['edit']->id);
                if ($return && isset($user_check)){
                    if ($user->id == $user_check->id) {
                        $return = $this->check_permission($user->role_id,$related_permissions['editSelf']->id);
                    } else {
                        $return = $this->check_permission($user->role_id,$related_permissions['editOther']->id);
                    }
                }
                break;

            case 'create':
                $related_permissions = $this->get_permissions_type_section('create','user');
                $return = $this->check_permission($user->role_id,$related_permissions['create']->id);
                break;

            case 'delete':
                $related_permissions = $this->get_permissions_type_section('delete','user');
                $return = $this->check_permission($user->role_id,$related_permissions['delete']->id);
                if ($return && isset($user_check)){
                    if ($user->id == $user_check->id) {
                        $return = $this->check_permission($user->role_id,$related_permissions['deleteSelf']->id);
                    } else {
                        $return = $this->check_permission($user->role_id,$related_permissions['deleteOther']->id);
                    }
                }
                break;
        }
        if ($return) {
            $return = $this->check_permission($user->role_id, $permission->id);
        }
        return $return;
    }

    private function can_do_ingredient(String $action, User $user, Ingredient $about = null){
        $return = false;
        $permission = $this->get_details_permission($action, 'ingredient');
        switch ($permission->type) {
            case 'view':
                $related_permissions = $this->get_permissions_type_section('view','ingredient');
                $return = $this->check_permission($user->role_id,$related_permissions['view']->id);
                break;

            case 'edit':
                $related_permissions = $this->get_permissions_type_section('edit','ingredient');
                $return = $this->check_permission($user->role_id,$related_permissions['edit']->id);
                break;

            case 'create':
                $related_permissions = $this->get_permissions_type_section('create','ingredient');
                $return = $this->check_permission($user->role_id,$related_permissions['create']->id);
                break;

            case 'delete':
                $related_permissions = $this->get_permissions_type_section('delete','ingredient');
                $return = $this->check_permission($user->role_id,$related_permissions['delete']->id);
                break;
        }
        if ($return) {
            $return = $this->check_permission($user->role_id, $permission->id);
        }
        return $return;
    }

    private function can_do_recipe(String $action, User $user, Recipe $recipe = null){
        #TODO: new permission system
        $return = false;
        $permission = $this->get_details_permission($action, 'recipe');
        switch ($permission->type) {
            case 'view':
                $related_permissions = $this->get_permissions_type_section('view','recipe');
                $return = $this->check_permission($user->role_id,$related_permissions['view']->id);
                if ($return && isset($recipe)){
                    if ($user->id == $recipe->user_id) {
                        $return = $this->check_permission($user->role_id,$related_permissions['viewMine']->id);
                    } else if ($recipe->visibility()->first()->name == 'Public') {
                        $return = $this->check_permission($user->role_id,$related_permissions['viewPublic']->id);
                    } else {
                        $return = $this->check_permission($user->role_id,$related_permissions['viewOther']->id);
                    }
                }
                break;

            case 'edit':
                $related_permissions = $this->get_permissions_type_section('edit','recipe');
                $return = $this->check_permission($user->role_id,$related_permissions['edit']->id);
                if ($return && isset($recipe)){
                    if ($user->id == $recipe->user_id) {
                        $return = $this->check_permission($user->role_id,$related_permissions['editMine']->id);
                    } else if ($recipe->visibility()->first()->name == 'Public') {
                        $return = $this->check_permission($user->role_id,$related_permissions['editPublic']->id);
                    } else {
                        $return = $this->check_permission($user->role_id,$related_permissions['editOther']->id);
                    }
                }
                break;

            case 'create':
                $related_permissions = $this->get_permissions_type_section('create','recipe');
                $return = $this->check_permission($user->role_id,$related_permissions['create']->id);
                break;

            case 'delete':
                $related_permissions = $this->get_permissions_type_section('delete','recipe');
                $return = $this->check_permission($user->role_id,$related_permissions['delete']->id);
                if ($return && isset($recipe)){
                    if ($user->id == $recipe->user_id) {
                        $return = $this->check_permission($user->role_id,$related_permissions['deleteMine']->id);
                    } else if ($recipe->visibility()->first()->name == 'Public') {
                        $return = $this->check_permission($user->role_id,$related_permissions['deletePublic']->id);
                    } else {
                        $return = $this->check_permission($user->role_id,$related_permissions['deleteOther']->id);
                    }
                }
                break;
        }
        if ($return) {
            $return = $this->check_permission($user->role_id, $permission->id);
        }
        return $return;
    }

    private function get_details_permission(String $permission, String $section)
    {
        return DB::table('permissions')
            ->select('id', 'type', 'key')
            ->where([
                ['key', '=', $permission],
                ['section','=',$section]
            ])
            ->first();
    }

    private function get_permissions_type_section(String $type, String $section)
    {
        return DB::table('permissions')
            ->select('id', 'key')
            ->where([
                ['type', '=', $type],
                ['section','=',$section]
            ])
            ->get()
            ->keyBy('key');
    }

    private function check_permission(int $role_id, int $permission_id)
    {
        $check_permission = DB::table('permission_role')
            ->where([
                ['role_id', '=', $role_id],
                ['permission_id','=',$permission_id]
            ])
            ->get();
        return count($check_permission) == 1 ? true : false;
    }
}
