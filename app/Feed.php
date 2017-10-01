<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
    const IN_QUEUE = 'in_queue';
    const EXTRACTING = 'extracting';
    const PARSING = 'parsing';
    const DONE = 'done';
    const ERROR = 'error';

    protected $fillable = ['filename', 'status'];

    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function logs()
    {
        return $this->hasManyThrough(Log::class, Package::class);
    }
}
