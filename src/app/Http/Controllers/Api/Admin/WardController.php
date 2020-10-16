<?php

namespace VCComponent\Laravel\Geography\Http\Controllers\Api\Admin;

use VCComponent\Laravel\Geography\Traits\Helpers;
use VCComponent\Laravel\Geography\Traits\WardAdminMethods;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;

class WardController extends ApiController
{
    use WardAdminMethods, Helpers;

    protected $repository;
    protected $entity;
    protected $validator;
    protected $transformer;
    protected $constraint;
}
