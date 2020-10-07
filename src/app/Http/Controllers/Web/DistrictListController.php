<?php

namespace VCComponent\Laravel\Geography\Http\Controllers\Web;

use Illuminate\Routing\Controller as BaseController;
use VCComponent\Laravel\Geography\Contracts\ViewDistrictListControllerInterface;
use Illuminate\Http\Request;


class DistrictListController extends BaseController implements ViewDistrictListControllerInterface
{
    public function __construct()
    {
        $this->middleware('example.middleware', ['except' => []]);
    }

    public function index(Request $request)
    {

    }
}
