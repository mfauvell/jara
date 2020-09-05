<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Ingredient extends Model
{
    use SoftDeletes;

    /**
     * To enable softdeletes
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function recipes() {
        return $this->belongsToMany(Recipe::class)->withPivot('quantity', 'unit', 'section');
    }

    public function images() {
        return $this->morphOne(Image::class,'imagable');
    }

    public static function search(Array $params) {
        $where = array();
        // $where[] = array('deleted_at', 'IS NOT NULL', null);
        if (isset($params['name']) && $params['name'] != '') {
            $where[] = array('name', 'like', '%' . $params['name'] . '%');
        }
        return Ingredient::where($where)->get();
    }
}
