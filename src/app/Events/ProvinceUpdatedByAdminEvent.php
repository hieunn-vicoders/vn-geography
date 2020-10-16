<?php

namespace VCComponent\Laravel\Geography\Events;

use Illuminate\Queue\SerializesModels;

class ProvinceUpdatedByAdminEvent
{
    use SerializesModels;

    public $province;

    public function __construct($province)
    {
        $this->province = $province;
    }
}
