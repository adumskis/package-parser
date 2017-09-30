<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
    protected $fillable = ['filename'];

    public function packages()
    {
        return $this->hasMany(Package::class);
    }
}
