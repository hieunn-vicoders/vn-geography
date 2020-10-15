<?php

namespace VCComponent\Laravel\Geography\Traits;

use Exception;
use Illuminate\Http\Request;
use VCComponent\Laravel\Geography\Events\DistrictCreatedEvent;
use VCComponent\Laravel\Geography\Events\DistrictDeletedEvent;
use VCComponent\Laravel\Geography\Events\DistrictUpdatedEvent;
use VCComponent\Laravel\Geography\Repositories\DistrictRepository;
use VCComponent\Laravel\Geography\Transformers\DistrictTransformer;
use VCComponent\Laravel\Vicoders\Core\Exceptions\NotFoundException;
use VCComponent\Laravel\Vicoders\Core\Exceptions\PermissionDeniedException;

trait DistrictFrontendMethods
{
    public function __construct(DistrictRepository $repository, Request $request)
    {
        $this->repository = $repository;
        $this->entity     = $repository->getEntity();

        if (config('geography.auth_middleware')['frontend']['middleware']) {
            $this->middleware(
                config('geography.auth_middleware')['frontend']['middleware'],
                ['except' => config('geography.auth_middleware.frontend.except')]
            );
        }

        if (isset(config('geography.transformers')['district'])) {
            $this->transformer = config('geography.transformers.district');
        } else {
            $this->transformer = DistrictTransformer::class;
        }
    }

    public function index(Request $request)
    {
        $query = $this->entity;

        // $query = $this->applyQueryScope($query, 'type', $this->type);
        $query = $this->applyConstraintsFromRequest($query, $request);
        $query = $this->applySearchFromRequest($query, ['name'], $request);
        $query = $this->applyOrderByFromRequest($query, $request);

        $per_page   = $request->has('per_page') ? (int) $request->get('per_page') : 15;
        $districts = $query->paginate($per_page);

        if ($request->has('includes')) {
            $transformer = new $this->transformer(explode(',', $request->get('includes')));
        } else {
            $transformer = new $this->transformer;
        }

        return $this->response->paginator($districts, $transformer);
    }

    function list(Request $request) {
        $query = $this->entity;

        // $query = $this->applyQueryScope($query, 'type', $this->type);
        $query = $this->applyConstraintsFromRequest($query, $request);
        $query = $this->applySearchFromRequest($query, ['name'], $request);
        $query = $this->applyOrderByFromRequest($query, $request);

        $districts = $query->get();

        if ($request->has('includes')) {
            $transformer = new $this->transformer(explode(',', $request->get('includes')));
        } else {
            $transformer = new $this->transformer;
        }

        return $this->response->collection($districts, $transformer);
    }

    public function show(Request $request, $id)
    {
        $district = $this->repository->findWhere(['id' => $id])->first();
        if (!$district) {
            throw new Exception("Quận hoặc huyện này không tồn tại", 1);
        }

        if (config('geography.auth_middleware')['frontend']['middleware']) {
            $user = $this->getAuthenticatedUser();
            if (!$this->entity->ableToShow($user, $id)) {
                throw new PermissionDeniedException();
            }
        }

        if ($request->has('includes')) {
            $transformer = new $this->transformer(explode(',', $request->get('includes')));
        } else {
            $transformer = new $this->transformer;
        }

        return $this->response->item($district, $transformer);
    }

    public function store(Request $request)
    {
        if (config('geography.auth_middleware')['frontend']['middleware'] !== '') {
            $user = $this->getAuthenticatedUser();
            if (!$this->entity->ableToCreate($user)) {
                throw new PermissionDeniedException();
            }
        }

        $data = $request->all();

        $this->validator->isValid($data, 'RULE_ADMIN_CREATE');

        $district = $this->repository->create($data);
        $district->save();

        event(new DistrictCreatedEvent($district));

        return $this->response->item($district, new $this->transformer);
    }

    public function update(Request $request, $id)
    {
        $district = $this->repository->findWhere(['id' => $id, 'type' => $this->type])->first();
        if (!$district) {
            throw new NotFoundException(title_case($this->type) . ' entity');
        }

        if (config('geography.auth_middleware')['frontend']['middleware'] !== '') {
            $user = $this->getAuthenticatedUser();
            if (!$this->entity->ableToUpdateItem($user, $id)) {
                throw new PermissionDeniedException();
            }
        }

        $data = $request->all();

        $this->validator->isValid($data, 'RULE_ADMIN_UPDATE');

        $district = $this->repository->update($data, $id);

        if ($request->has('status')) {
            $district->status = $request->get('status');
            $district->save();
        }

        event(new DistrictUpdatedEvent($district));

        return $this->response->item($district, new $this->transformer);
    }

    public function destroy(Request $request, $id)
    {
        $district = $this->repository->findWhere(['id' => $id])->first();
        if (!$district) {
            throw new NotFoundException('District');
        }

        if (config('geography.auth_middleware')['frontend']['middleware'] !== '') {
            $user = $this->getAuthenticatedUser();
            if (!$this->entity->ableToDelete($user, $id)) {
                throw new PermissionDeniedException();
            }
        }

        $this->repository->delete($id);

        event(new DistrictDeletedEvent());

        return $this->success();
    }
}
