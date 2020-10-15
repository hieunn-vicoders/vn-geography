<?php

namespace VCComponent\Laravel\Geography\Transformers;

use League\Fractal\TransformerAbstract;

class WardTransformer extends TransformerAbstract
{
    protected $availableIncludes = [];

    public function __construct($includes = [])
    {
        $this->setDefaultIncludes($includes);
    }

    public function transform($model)
    {
        return [
            'id'                 => (int) $model->id,
            'name'               => $model->name,
            'type'               => $model->type,
            'status'             => $model->status,
            'district name'      => $model->district->name,
            'district location' => $model->district->location,
            'district type'      => $model->district->type,
            'district status'    => $model->district->status,
            'province name'      => $model->district->province->name,
            'province type'      => $model->district->province->type,
            'province status'    => $model->district->province->status,
            'timestamps' => [
                'created_at' => $model->created_at,
                'updated_at' => $model->updated_at,
            ],
        ];
    }
}
