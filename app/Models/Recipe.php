<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recipe extends Model
{
    use SoftDeletes;

    /**
     * To enable softdeletes
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    // protected $with = ['visibility'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function steps() {
        return $this->hasMany(Step::class);
    }

    public function ingredients() {
        return $this->belongsToMany(Ingredient::class)->withPivot('quantity', 'unit', 'section');
    }

    public function images() {
        return $this->morphMany(Image::class,'imagable');
    }

    public function visibility() {
        return $this->belongsTo(Visibility::class);
    }

    public function types() {
        return $this->belongsToMany(RecipeType::class, 'recipe_recipe_type');
    }

    public static function search(Array $params = []) {
        $user_id = auth()->user()->id;
        $where = array();
        $whereIngredient = array();
        if (isset($params['title']) && $params['title'] != '') {
            $where[] = array('title', 'like', '%' . $params['title'] . '%');
        }
        $whereIngredient = array();
        if (isset($params['ingredient']) && $params['ingredient'] != '') {
            $whereIngredient[] = array('ingredient_id', '=', $params['ingredient']);
        }
        if ($params['admin']){
            $query = Recipe::where($where);
        } else {
            $query = Recipe::where(function($q) use ($user_id) {
                $q->whereHas('visibility',function($q) {
                    $q->where('name','=','Public');
                });
                $q->orWhere('user_id', '=', $user_id);
            })
            ->where($where);
        }
        $query->where(function($q) use ($whereIngredient){
            if ($whereIngredient) {
                $q->whereHas('ingredients', function($q) use ($whereIngredient){
                    $q->where($whereIngredient);
                });
            }
        });
        if (isset($params['onlyPublic']) && $params['onlyPublic']){
            $query = $query->whereHas('visibility',function($q) {
                $q->where('name','=','Public');
            });
        } else if (isset($params['onlyPrivate']) && $params['onlyPrivate']) {
            $query = $query->whereHas('visibility',function($q) {
                $q->where('name','=','Private');
            });
        }
        if (isset($params['latest']) && $params['latest']){
            $query = $query->latest('updated_at');
        }
        if (isset($params['limit']) && $params['limit'] != '') {
            $query = $query->take($params['limit']);
        }
        return $query->get();
    }
}
