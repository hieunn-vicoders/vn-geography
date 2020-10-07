<?php

namespace VCComponent\Laravel\Geography\Http\Controllers\Web;

use Illuminate\Routing\Controller as BaseController;
use VCComponent\Laravel\Geography\Contracts\ViewCityDetailControllerInterface;
use Illuminate\Http\Request;

class CityDetailController extends BaseController implements ViewCityDetailControllerInterface
{
    public function __construct()
    {
        $this->middleware('example.middleware', ['except' => []]);
    }

    public function show($slug, Request $request)
    {

    }
}
