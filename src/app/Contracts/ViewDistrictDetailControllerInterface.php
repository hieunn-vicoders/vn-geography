<?php

namespace VCComponent\Laravel\Geography\Contracts;

use Illuminate\Http\Request;

interface ViewDistrictDetailControllerInterface
{
    public function show($id, Request $request);
}
