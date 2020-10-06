<?php

namespace VCComponent\Laravel\Geography\Contracts;

use Illuminate\Http\Request;

interface ViewWardDetailControllerInterface
{
    public function show($id, Request $request);
}
