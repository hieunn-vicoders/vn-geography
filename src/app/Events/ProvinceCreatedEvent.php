<?php

namespace VCComponent\Laravel\Geography\Events;

use Illuminate\Queue\SerializesModels;

class ProvinceCreatedEvent
{
    use SerializesModels;

    public $province;

    public function __construct($province)
    {
        $this->province = $province;
    }
}
