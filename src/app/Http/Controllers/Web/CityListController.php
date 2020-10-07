<?php

namespace VCComponent\Laravel\Geography\Http\Controllers\Web;

use Illuminate\Routing\Controller as BaseController;
use VCComponent\Laravel\Geography\Contracts\ViewCityListControllerInterface;
use Illuminate\Http\Request;


class CityListController extends BaseController implements ViewCityListControllerInterface
{
    public function __construct()
    {
        $this->middleware('example.middleware', ['except' => []]);
    }

    public function index(Request $request)
    {

    }
}
