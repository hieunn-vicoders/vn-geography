<?php

namespace VCComponent\Laravel\Geography\Http\Controllers\Web;

use Illuminate\Routing\Controller as BaseController;
use VCComponent\Laravel\Geography\Contracts\ViewDistrictDetailControllerInterface;
use Illuminate\Http\Request;


class DistrictDetailController extends BaseController implements ViewDistrictDetailControllerInterface
{
    public function __construct()
    {
        $this->middleware('example.middleware', ['except' => []]);
    }

    public function show($slug, Request $request)
    {

    }
}
