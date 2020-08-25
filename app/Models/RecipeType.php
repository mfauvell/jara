<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipeType extends Model
{
    public function recipes(){
        return $this->belongsToMany(Recipe::class, 'recipe_recipe_type');
    }
}
