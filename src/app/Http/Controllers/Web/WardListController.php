<?php

namespace VCComponent\Laravel\Geography\Http\Controllers\Web;

use Illuminate\Routing\Controller as BaseController;
use VCComponent\Laravel\Geography\Contracts\ViewWardListControllerInterface;
use Illuminate\Http\Request;


class WardListController extends BaseController implements ViewWardListControllerInterface
{
    public function __construct()
    {
        $this->middleware('example.middleware', ['except' => []]);
    }

    public function index(Request $request)
    {

    }
}
