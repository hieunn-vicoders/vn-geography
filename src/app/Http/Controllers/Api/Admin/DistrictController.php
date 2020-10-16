<?php

namespace VCComponent\Laravel\Geography\Http\Controllers\Api\Admin;

use VCComponent\Laravel\Geography\Traits\DistrictAdminMethods;
use VCComponent\Laravel\Geography\Traits\Helpers;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;

class DistrictController extends ApiController
{
    use DistrictAdminMethods, Helpers;

    protected $repository;
    protected $entity;
    protected $validator;
    protected $transformer;
    protected $constraint;
}
