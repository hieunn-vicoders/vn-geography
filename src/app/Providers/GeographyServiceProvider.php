<?php

namespace VCComponent\Laravel\Geography\Providers;

use Illuminate\Support\ServiceProvider;
use VCComponent\Laravel\Geography\Contracts\ViewDistrictControllerInterface;
use VCComponent\Laravel\Geography\Contracts\ViewProvinceControllerInterface;
use VCComponent\Laravel\Geography\Contracts\ViewWardControllerInterface;
use VCComponent\Laravel\Geography\Repositories\DistrictRepository;
use VCComponent\Laravel\Geography\Repositories\DistrictRepositoryEloquent;
use VCComponent\Laravel\Geography\Repositories\ProvinceRepository;
use VCComponent\Laravel\Geography\Repositories\ProvinceRepositoryEloquent;
use VCComponent\Laravel\Geography\Repositories\WardRepository;
use VCComponent\Laravel\Geography\Repositories\WardRepositoryEloquent;

class GeographyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');

        $this->publishes([
            __DIR__ . '/../../config/geography.php' => config_path('geography.php'),
        ], 'config');

    }

    /**
     * Register any package services
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ProvinceRepository::class, ProvinceRepositoryEloquent::class);
        $this->app->bind(DistrictRepository::class, DistrictRepositoryEloquent::class);
        $this->app->bind(WardRepository::class, WardRepositoryEloquent::class);
        $this->registerControllers();

        include __DIR__ . '/../../database/seeds/DatabaseSeeder.php';
        include __DIR__ . '/../../database/seeds/ProvincesTableSeeder.php';
        include __DIR__ . '/../../database/seeds/DistrictsTableSeeder.php';
        include __DIR__ . '/../../database/seeds/WardsTableSeeder.php';
    }

    private function registerControllers()
    {
        $this->app->bind(ViewProvinceControllerInterface::class, ViewProvinceController::class);
        $this->app->bind(ViewDistrictControllerInterface::class, ViewDistrictController::class);
        $this->app->bind(ViewWardControllerInterface::class, ViewWardController::class);
    }
}
