<?php

namespace VCComponent\Laravel\Geography\Events;

use Illuminate\Queue\SerializesModels;

class DistrictCreatedByAdminEvent
{
    use SerializesModels;

    public $district;

    public function __construct($district)
    {
        $this->district = $district;
    }
}
