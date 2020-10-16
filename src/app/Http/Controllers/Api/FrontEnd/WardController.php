<?php

namespace VCComponent\Laravel\Geography\Http\Controllers\Api\FrontEnd;

use VCComponent\Laravel\Geography\Traits\Helpers;
use VCComponent\Laravel\Geography\Traits\WardFrontendMethods;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;

class WardController extends ApiController
{
    use WardFrontendMethods, Helpers;

    protected $repository;
    protected $entity;
    protected $validator;
    protected $transformer;
}
