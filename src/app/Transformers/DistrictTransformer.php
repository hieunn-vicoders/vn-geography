<?php

namespace VCComponent\Laravel\Geography\Transformers;

use League\Fractal\TransformerAbstract;

class DistrictTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['wards'];

    public function __construct($includes = [])
    {
        $this->setDefaultIncludes($includes);
    }

    public function transform($model)
    {
        return [
            'id'              => (int) $model->id,
            'name'            => $model->name,
            'type'            => $model->type,
            'status'          => $model->status,
            'location'        => $model->location,
            'province'        => $model->province->name,
            'province type'   => $model->province->type,
            'province status' => $model->province->status,
            'timestamps'      => [
                'created_at'  => $model->created_at,
                'updated_at'  => $model->updated_at,
            ],
        ];
    }

    public function includeWards($model)
    {
        return $this->collection($model->wards, new WardTransformer());
    }
}
