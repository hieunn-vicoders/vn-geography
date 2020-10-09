<?php

use Illuminate\Support\Facades\Route;

Route::prefix(config('provinces.namespace'))->middleware('web')->group(function () {
    Route::get('/provinces', 'VCComponent\Laravel\Geography\Contracts\ViewProvinceListControllerInterface@index');
    Route::get('/provinces/{slug}', 'VCComponent\Laravel\Geography\Contracts\ViewProvinceDetailControllerInterface@show');
    Route::get('/provinces/{slug}/districts', 'VCComponent\Laravel\Geography\Contracts\ViewDistrictListControllerInterface@index');
    Route::get('/provinces/{slug}/districts/{slug}', 'VCComponent\Laravel\Geography\Contracts\ViewDistrictDetailControllerInterface@show');
    Route::get('/provinces/{slug}/districts{slug}/wards', 'VCComponent\Laravel\Geography\Contracts\ViewWardListControllerInterface@index');
    Route::get('/provinces/{slug}/districts/{slug}/wards/{slug}', 'VCComponent\Laravel\Geography\Contracts\ViewWardDetailControllerInterface@show');
});
