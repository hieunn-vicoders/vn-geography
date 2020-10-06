<?php

namespace VCComponent\Laravel\Geography\Contracts;

use Illuminate\Http\Request;

interface ViewCityListControllerInterface
{
    public function index(Request $request);
}
