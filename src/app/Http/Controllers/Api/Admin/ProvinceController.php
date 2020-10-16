<?php

namespace VCComponent\Laravel\Geography\Http\Controllers\Api\Admin;

use VCComponent\Laravel\Geography\Traits\Helpers;
use VCComponent\Laravel\Geography\Traits\ProvinceAdminMethods;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;

class ProvinceController extends ApiController
{
    use ProvinceAdminMethods, Helpers;

    protected $repository;
    protected $entity;
    protected $validator;
    protected $transformer;
    protected $constraint;
}
