<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = ['feed_id', 'taken_at', 'original_filename', 'is_parsed', 'etot_kwh'];


    protected $dates = [
        'taken_at',
    ];

    public $timestamps = false;

    public function feed()
    {
        return $this->belongsTo(Feed::class);
    }

    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    public function getFormattedTakenAtAttribute()
    {
        return $this->taken_at->toDateTimeString();
    }
}
