<?php

namespace VCComponent\Laravel\Geography\Entities;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
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

    public function districts()
    {
        return $this->hasMany(District::class, 'city_id');
    }
}
