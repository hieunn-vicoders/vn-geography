<?php

namespace VCComponent\Laravel\Geography\Events;

use Illuminate\Queue\SerializesModels;

class DistrictDeletedEvent
{
    use SerializesModels;

    public function __construct()
    {

    }
}
