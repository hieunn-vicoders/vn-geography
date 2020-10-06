<?php

namespace VCComponent\Laravel\Geography\Http\Controllers\Web;

use Illuminate\Routing\Controller as BaseController;
use VCComponent\Laravel\Geography\Contracts\ViewWardDetailControllerInterface;

class WardDetailController extends BaseController implements ViewWardDetailControllerInterface
{
    public function __construct()
    {
        $this->middleware('example.middleware', ['except' => []]);
    }
}
