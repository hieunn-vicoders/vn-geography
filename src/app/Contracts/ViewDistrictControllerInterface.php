<?php

namespace VCComponent\Laravel\Geography\Contracts;

use Illuminate\Http\Request;

interface ViewDistrictControllerInterface
{
    public function show($id, Request $request);
    public function index(Request $request);
}
