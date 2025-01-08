<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Banner extends Model
{
    protected $table = 'banners';
    protected $primaryKey = 'banner_id';
    protected $fillable = [
        'title',
        'description',
        'image_url',
        'link',
        'start_datetime',
        'end_datetime'
    ];

    protected $dates = [
        'start_datetime',
        'end_datetime'
    ];
    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime'
    ];
    public function scopeActive($query)
    {
        $now = now();
        return $query->where('start_datetime', '<=', $now)
            ->where('end_datetime', '>=', $now);
    }

    public function getStatusAttribute()
    {
        $now = now();
        if ($this->start_datetime <= $now && $this->end_datetime >= $now) {
            return 'ongoing';
        } elseif ($this->start_datetime > $now) {
            return 'upcoming';
        } else {
            return 'expired';
        }
    }
}
