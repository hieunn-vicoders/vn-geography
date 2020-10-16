<?php

namespace VCComponent\Laravel\Geography\Http\Controllers\Api\FrontEnd;

use VCComponent\Laravel\Geography\Traits\Helpers;
use VCComponent\Laravel\Geography\Traits\ProvinceFrontendMethods;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;

class ProvinceController extends ApiController
{
    use ProvinceFrontendMethods, Helpers;

    protected $repository;
    protected $entity;
    protected $validator;
    protected $transformer;
}
