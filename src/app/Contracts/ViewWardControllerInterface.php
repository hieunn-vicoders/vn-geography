<?php

namespace VCComponent\Laravel\Geography\Contracts;

use Illuminate\Http\Request;

interface ViewWardControllerInterface
{
    public function show($id, Request $request);
    public function index(Request $request);
}
