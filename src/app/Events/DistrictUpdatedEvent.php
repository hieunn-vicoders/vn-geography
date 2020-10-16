<?php

namespace VCComponent\Laravel\Geography\Events;

use Illuminate\Queue\SerializesModels;

class DistrictUpdatedEvent
{
    use SerializesModels;

    public $district;

    public function __construct($district)
    {
        $this->district = $district;
    }
}
