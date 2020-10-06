<?php

namespace VCComponent\Laravel\Geography\Http\Controllers\Web;

use Illuminate\Routing\Controller as BaseController;
use VCComponent\Laravel\Geography\Contracts\ViewCityDetailControllerInterface;

class CityDetailController extends BaseController implements ViewCityDetailControllerInterface
{
    public function __construct()
    {
        $this->middleware('example.middleware', ['except' => []]);
    }
}
