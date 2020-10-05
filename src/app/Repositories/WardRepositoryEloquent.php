<?php

namespace HaiCS\Laravel\Generator\Repositories;

use HaiCS\Laravel\Generator\Entities\Ward;
use HaiCS\Laravel\Generator\Repositories\WardRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class AccountantRepositoryEloquent.
 */
class WardRepositoryEloquent extends BaseRepository implements WardRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Ward::class;
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
