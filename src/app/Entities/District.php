<?php

namespace VCComponent\Laravel\Geography\Entities;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    const STATUS_PENDING = 2;
    const STATUS_ACTIVE = 1;

    protected $fillable = [
        'name',
        'type',
        'status',
        'area',
        'thumbnail',
        'content',
    ];

    public function city()
    {
        return $this->belongsTo(Province::class, 'city_id');
    }

    public function wards()
    {
        return $this->hasMany(Ward::class, 'district_id');
    }
}
