<?php

return [

    'namespace' => env('GEOGRAPHY_COMPONENT_NAMESPACE', ''),

    'models' => [
        'city' => VCComponent\Laravel\Geography\Entities\City::class,
        'district' => VCComponent\Laravel\Geography\Entities\District::class,
        'ward' => VCComponent\Laravel\Geography\Entities\Ward::class,
    ],

    'transformers' => [
        'city' => VCComponent\Laravel\Geography\Transformers\CityTransformer::class,
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
