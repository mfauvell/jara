<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * To enable softdeletes
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function recipes() {
        return $this->hasMany(Recipe::class);
    }

    public function role() {
        return $this->belongsTo(Role::class);
    }

    public static function search(Array $params) {
        $where = array();
        // $where[] = array('deleted_at', 'IS NOT NULL', null);
        if (isset($params['name']) && $params['name'] != '') {
            $where[] = array('name', 'like', '%' . $params['name'] . '%');
        }
        if (isset($params['email']) && $params['email'] != '') {
            $where[] = array('email', 'like', '%' . $params['email'] . '%');
        }
        if (isset($params['role_id']) && $params['role_id'] != '') {
            $where[] = array('role_id', '=', $params['role_id']);
        }
        return DB::table('users')->select('id', 'name', 'email', 'role_id')->where($where)->whereNull('deleted_at')->get();
    }
}
