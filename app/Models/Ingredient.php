<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
