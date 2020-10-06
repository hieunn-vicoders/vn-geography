<?php

namespace VCComponent\Laravel\Geography\Contracts;

use Illuminate\Http\Request;

interface ViewWardListControllerInterface
{
    public function index(Request $request);
}
