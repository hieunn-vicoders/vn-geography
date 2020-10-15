<?php

namespace VCComponent\Laravel\Geography\Transformers;

use League\Fractal\TransformerAbstract;

class ProvinceTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['districts'];

    public function __construct($includes = [])
    {
        $this->setDefaultIncludes($includes);
    }

    public function transform($model)
    {
        return [
            'id'             => (int) $model->id,
            'name'           => $model->name,
            'type'           => $model->type,
            'status'         => $model->status,
            'timestamps' => [
                'created_at' => $model->created_at,
                'updated_at' => $model->updated_at,
            ],
        ];
    }

    public function includeDistricts($model)
    {
        return $this->collection($model->districts, new DistrictTransformer());
    }
}
