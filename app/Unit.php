<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = ['package_id', 'unit_id', 'data'];

    protected $attributes = [
        'data' => '{}'
    ];

    protected $casts = [
        'data' => 'array',
    ];
}
