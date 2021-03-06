<?php
namespace App\Models;

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
            case User::class:
                $return = $this->can_do_user($action,$user,$about);
                break;

            case Ingredient::class:
                $return = $this->can_do_ingredient($action,$user,$about);
                break;

            case Recipe::class:
                $return = $this->can_do_recipe($action,$user,$about);
                break;

            default:
                $return = false;
                break;
        }

        return $return;
    }

    private function can_do_user(String $action, User $user, $about = null){
        $return = false;
        switch ($action) {
            case 'view':
                $return = $user->role()->first()->name == 'SuperAdmin' ? true : false;
                break;

            case 'edit':
                $return = $user->role()->first()->name == 'SuperAdmin' ? true : false;
                break;

            case 'create':
                $return = $user->role()->first()->name == 'SuperAdmin' ? true : false;
                break;

            case 'delete':
                $return = $user->role()->first()->name == 'SuperAdmin' ? true : false;
                break;

            default:
                # code...
                break;
        }
        return $return;
    }

    private function can_do_ingredient(String $action, User $user, $about = null){
        $return = false;
        switch ($action) {
            case 'view':
                $return = true;
                break;

            case 'edit':
                $return = true;
                break;

            case 'create':
                $return =true;
                break;

            case 'delete':
                $return = in_array($user->role()->first()->name, ['SuperAdmin', 'Admin']) ? true : false;
                break;

            default:
                # code...
                break;
        }
        return $return;
    }

    private function can_do_recipe(String $action, User $user, $about = null){
        $return = false;
        switch ($action) {
            case 'view':
                $return = true;
                if ($about) {
                    if ($user->role()->first()->name == "SuperAdmin") {
                        $return = true;
                    } else {
                        if ($about->visibility()->first()->name == 'Public') {
                            $return = true;
                        } else {
                            $return = $user->id == $about->user()->first()->id;
                        }
                    }
                }
                break;

            case 'edit':
                $return = false;
                if ($about) {
                    if ($user->role()->first()->name == "SuperAdmin") {
                        $return = true;
                    } else {
                        if ($about->visibility()->first()->name == 'Public' && $user->role()->first()->name =="Admin") {
                            $return = true;
                        } else {
                            $return = $user->id == $about->user()->first()->id;
                        }
                    }
                }
                break;

            case 'create':
                $return =true;
                break;

            case 'delete':
                $return = false;
                if ($user->role()->first()->name == "SuperAdmin") {
                    $return = true;
                }
                break;

            default:
                # code...
                break;
        }

        return $return;
    }

}
