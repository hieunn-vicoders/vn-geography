<?php

namespace VCComponent\Laravel\Geography\Repositories;

use VCComponent\Laravel\Geography\Entities\District;
use VCComponent\Laravel\Geography\Repositories\DistrictRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class AccountantRepositoryEloquent.
 */
class DistrictRepositoryEloquent extends BaseRepository implements DistrictRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return District::class;
    }

    public function getEntity()
    {
        return $this->model;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
