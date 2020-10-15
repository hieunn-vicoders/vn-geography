<?php

namespace VCComponent\Laravel\Geography\Entities;

use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    const STATUS_PENDING = 2;
    const STATUS_ACTIVE = 1;

    const TYPE_WARD    = 1; // Phường
    const TYPE_COMMUNE = 2; // Xã
    const TYPE_TOWN    = 3; // Thị trấn

    protected $table = 'wards';

    protected $fillable = [
        'name',
        'type',
    ];

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
