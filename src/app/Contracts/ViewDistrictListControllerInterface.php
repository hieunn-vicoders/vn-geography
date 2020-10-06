<?php

namespace VCComponent\Laravel\Geography\Contracts;

use Illuminate\Http\Request;

interface ViewDistrictListControllerInterface
{
    public function index(Request $request);
}
