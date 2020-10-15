<?php

namespace VCComponent\Laravel\Geography\Events;

use Illuminate\Queue\SerializesModels;

class ProvinceDeletedEvent
{
    use SerializesModels;

    public function __construct()
    {

    }
}
