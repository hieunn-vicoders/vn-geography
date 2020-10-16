<?php

namespace VCComponent\Laravel\Geography\Http\Controllers\Web;

use Illuminate\Routing\Controller as BaseController;
use VCComponent\Laravel\Geography\Contracts\ViewWardControllerInterface;
use Illuminate\Http\Request;


class WardController extends BaseController implements ViewWardControllerInterface
{
    public function __construct()
    {
        $this->middleware('example.middleware', ['except' => []]);
    }

    public function show($slug, Request $request)
    {

    }

    public function index(Request $request)
    {

    }
}
