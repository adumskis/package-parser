<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = ['package_id'];

    protected $attributes = [
        'data' => '{}'
    ];

    protected $casts = [
        'data' => 'array',
    ];
}
