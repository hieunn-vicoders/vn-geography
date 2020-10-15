<?php

namespace VCComponent\Laravel\Geography\Http\Controllers\Api\FrontEnd;

use VCComponent\Laravel\Geography\Traits\DistrictFrontendMethods;
use VCComponent\Laravel\Geography\Traits\Helpers;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;

class DistrictController extends ApiController
{
    use DistrictFrontendMethods, Helpers;

    protected $repository;
    protected $entity;
    protected $validator;
    protected $transformer;
}
