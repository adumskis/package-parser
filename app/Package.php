<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = ['document_id', 'taken_at'];

    protected $attributes = [
        'total' => '{}'
    ];

    protected $casts = [
        'total' => 'array',
    ];
}
