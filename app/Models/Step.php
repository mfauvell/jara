<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Step extends Model
{
    use SoftDeletes;

    /**
     * To enable softdeletes
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $with = ['images'];

    public function recipe() {
        return $this->belongsTo(Recipe::class);
    }

    public function images() {
        return $this->morphMany(Image::class,'imagable');
    }
}
