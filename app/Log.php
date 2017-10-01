<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = ['package_id', 'unit_id', 'etot_kwh'];

    public $timestamps = false;

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
