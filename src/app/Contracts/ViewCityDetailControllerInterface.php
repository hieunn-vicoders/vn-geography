<?php

namespace VCComponent\Laravel\Geography\Contracts;

use Illuminate\Http\Request;

interface ViewCityDetailControllerInterface
{
    public function show($id, Request $request);
}
