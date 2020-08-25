<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visibility extends Model
{
    public function recipes(){
        return $this->hasMany(Recipe::class);
    }
}
