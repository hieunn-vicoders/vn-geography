<?php

namespace VCComponent\Laravel\Geography\Events;

use Illuminate\Queue\SerializesModels;

class WardUpdatedEvent
{
    use SerializesModels;

    public $ward;

    public function __construct($ward)
    {
        $this->ward = $ward;
    }
}
