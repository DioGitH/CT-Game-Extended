<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = ['order', 'name'];

    public function challenges()
    {
        return $this->hasMany(Challenge::class);
    }
}
