<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = ['feed_id', 'taken_at', 'original_filename', 'is_parsed'];

    protected $attributes = [
        'total' => '{}',
    ];

    protected $casts = [
        'total' => 'array',
    ];

    public function feed()
    {
        return $this->belongsTo(Feed::class);
    }
}
