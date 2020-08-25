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
}
