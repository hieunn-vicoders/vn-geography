<?php

namespace VCComponent\Laravel\Geography\Entities;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    const STATUS_PENDING = 2;
    const STATUS_ACTIVE = 1;

    const TYPE_CITY     = 1; // Thành Phố
    const TYPE_TOWNSHIP = 2; // Quận
    const TYPE_DISTRICT = 3; // Huyện
    const TYPE_TOWN     = 4; // Thị Xã

    protected $table = 'districts';

    protected $fillable = [
        'name',
        'type',
        'location',
        'province_id',
        'status',
        'order',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function wards()
    {
        return $this->hasMany(Ward::class);
    }
}
