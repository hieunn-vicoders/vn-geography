<?php

return [

    'namespace' => env('GEOGRAPHY_COMPONENT_NAMESPACE', ''),

    'models' => [
        'province' => VCComponent\Laravel\Geography\Entities\Province::class,
        'district' => VCComponent\Laravel\Geography\Entities\District::class,
        'ward' => VCComponent\Laravel\Geography\Entities\Ward::class,
    ],

    'transformers' => [
        'province' => VCComponent\Laravel\Geography\Transformers\ProvinceTransformer::class,
        'district' => VCComponent\Laravel\Geography\Transformers\DistrictTransformer::class,
        'ward' => VCComponent\Laravel\Geography\Transformers\WardTransformer::class,
    ],

    'auth_middleware' => [
        'admin'    => [
            'middleware' => '',
            'except'     => [],
        ],
        'frontend' => [
            'middleware' => '',
            'except'     => [],
        ],
    ],
];
