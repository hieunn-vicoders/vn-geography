<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->group(['prefix' => 'admin'], function ($api) {
        $api->get('provinces', 'VCComponent\Laravel\Geography\Http\Controllers\Api\Admin\ProvinceController@index');
        $api->get('provinces/{id}', 'VCComponent\Laravel\Geography\Http\Controllers\Api\Admin\ProvinceController@show');
        $api->post('provinces/list', 'VCComponent\Laravel\Geography\Http\Controllers\Api\Admin\ProvinceController@list');
        $api->post('provinces', 'VCComponent\Laravel\Geography\Http\Controllers\Api\Admin\ProvinceController@store');
        $api->put('provinces/{id}', 'VCComponent\Laravel\Geography\Http\Controllers\Api\Admin\ProvinceController@update');
        $api->put('provinces/{id}/status', 'VCComponent\Laravel\Geography\Http\Controllers\Api\Admin\ProvinceController@status');
        $api->delete('provinces/{id}', 'VCComponent\Laravel\Geography\Http\Controllers\Api\Admin\ProvinceController@destroy');

        $api->get('districts', 'VCComponent\Laravel\Geography\Http\Controllers\Api\Admin\DistrictController@index');
        $api->get('districts/{id}', 'VCComponent\Laravel\Geography\Http\Controllers\Api\Admin\DistrictController@show');
        $api->post('districts/list', 'VCComponent\Laravel\Geography\Http\Controllers\Api\Admin\DistrictController@list');
        $api->post('districts', 'VCComponent\Laravel\Geography\Http\Controllers\Api\Admin\DistrictController@store');
        $api->put('districts/{id}', 'VCComponent\Laravel\Geography\Http\Controllers\Api\Admin\DistrictController@update');
        $api->put('districts/{id}/status', 'VCComponent\Laravel\Geography\Http\Controllers\Api\Admin\DistrictController@status');
        $api->delete('districts/{id}', 'VCComponent\Laravel\Geography\Http\Controllers\Api\Admin\DistrictController@destroy');

        $api->get('wards', 'VCComponent\Laravel\Geography\Http\Controllers\Api\Admin\WardController@index');
        $api->get('wards/{id}', 'VCComponent\Laravel\Geography\Http\Controllers\Api\Admin\WardController@show');
        $api->post('wards/list', 'VCComponent\Laravel\Geography\Http\Controllers\Api\Admin\WardController@list');
        $api->post('wards', 'VCComponent\Laravel\Geography\Http\Controllers\Api\Admin\WardController@store');
        $api->put('wards/{id}', 'VCComponent\Laravel\Geography\Http\Controllers\Api\Admin\WardController@update');
        $api->put('wards/{id}/status', 'VCComponent\Laravel\Geography\Http\Controllers\Api\Admin\WardController@status');
        $api->delete('wards/{id}', 'VCComponent\Laravel\Geography\Http\Controllers\Api\Admin\WardController@destroy');
    });
    $api->get('provinces', 'VCComponent\Laravel\Geography\Http\Controllers\Api\FrontEnd\ProvinceController@index');
    $api->get('provinces/{id}', 'VCComponent\Laravel\Geography\Http\Controllers\Api\FrontEnd\ProvinceController@show');
    $api->post('provinces/list', 'VCComponent\Laravel\Geography\Http\Controllers\Api\FrontEnd\ProvinceController@list');

    $api->get('districts', 'VCComponent\Laravel\Geography\Http\Controllers\Api\FrontEnd\DistrictController@index');
    $api->get('districts/{id}', 'VCComponent\Laravel\Geography\Http\Controllers\Api\FrontEnd\DistrictController@show');
    $api->post('districts/list', 'VCComponent\Laravel\Geography\Http\Controllers\Api\FrontEnd\DistrictController@list');

    $api->get('wards', 'VCComponent\Laravel\Geography\Http\Controllers\Api\FrontEnd\WardController@index');
    $api->get('wards/{id}', 'VCComponent\Laravel\Geography\Http\Controllers\Api\FrontEnd\WardController@show');
    $api->post('wards/list', 'VCComponent\Laravel\Geography\Http\Controllers\Api\FrontEnd\WardController@list');
});











