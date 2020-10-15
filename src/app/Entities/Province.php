<?php

namespace VCComponent\Laravel\Geography\Entities;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    const STATUS_PENDING = 2;
    const STATUS_ACTIVE = 1;

    const TYPE_CITY       = 1;
    const TYPE_PROVINCIAL = 2;

    protected $table = 'provinces';

    protected $fillable = [
        'name',
        'type',
        'status',
        'order',
    ];

    public function districts()
    {
        return $this->hasMany(District::class);
    }
}
