<?php

namespace VCComponent\Laravel\Geography\Events;

use Illuminate\Queue\SerializesModels;

class WardDeletedEvent
{
    use SerializesModels;

    public function __construct()
    {

    }
}
