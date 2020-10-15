<?php

namespace VCComponent\Laravel\Geography\Traits;

use Exception;
use Illuminate\Http\Request;
use VCComponent\Laravel\Geography\Events\WardCreatedEvent;
use VCComponent\Laravel\Geography\Events\WardDeletedEvent;
use VCComponent\Laravel\Geography\Events\WardUpdatedEvent;
use VCComponent\Laravel\Geography\Repositories\WardRepository;
use VCComponent\Laravel\Geography\Transformers\WardTransformer;
use VCComponent\Laravel\Vicoders\Core\Exceptions\NotFoundException;
use VCComponent\Laravel\Vicoders\Core\Exceptions\PermissionDeniedException;

trait WardFrontendMethods
{
    public function __construct(WardRepository $repository, Request $request)
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
            $this->transformer = WardTransformer::class;
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
        $wards = $query->paginate($per_page);

        if ($request->has('includes')) {
            $transformer = new $this->transformer(explode(',', $request->get('includes')));
        } else {
            $transformer = new $this->transformer;
        }

        return $this->response->paginator($wards, $transformer);
    }

    function list(Request $request) {
        $query = $this->entity;

        // $query = $this->applyQueryScope($query, 'type', $this->type);
        $query = $this->applyConstraintsFromRequest($query, $request);
        $query = $this->applySearchFromRequest($query, ['name'], $request);
        $query = $this->applyOrderByFromRequest($query, $request);

        $wards = $query->get();

        if ($request->has('includes')) {
            $transformer = new $this->transformer(explode(',', $request->get('includes')));
        } else {
            $transformer = new $this->transformer;
        }

        return $this->response->collection($wards, $transformer);
    }

    public function show(Request $request, $id)
    {
        $ward = $this->repository->findWhere(['id' => $id])->first();
        if (!$ward) {
            throw new Exception("Xã hoặc phường này không tồn tại", 1);
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

        return $this->response->item($ward, $transformer);
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

        $ward = $this->repository->create($data);
        $ward->save();

        event(new WardCreatedEvent($ward));

        return $this->response->item($ward, new $this->transformer);
    }

    public function update(Request $request, $id)
    {
        $ward = $this->repository->findWhere(['id' => $id, 'type' => $this->type])->first();
        if (!$ward) {
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

        $ward = $this->repository->update($data, $id);

        if ($request->has('status')) {
            $ward->status = $request->get('status');
            $ward->save();
        }

        event(new WardUpdatedEvent($ward));

        return $this->response->item($ward, new $this->transformer);
    }

    public function destroy(Request $request, $id)
    {
        $ward = $this->repository->findWhere(['id' => $id])->first();
        if (!$ward) {
            throw new NotFoundException('Ward');
        }

        if (config('geography.auth_middleware')['frontend']['middleware'] !== '') {
            $user = $this->getAuthenticatedUser();
            if (!$this->entity->ableToDelete($user, $id)) {
                throw new PermissionDeniedException();
            }
        }

        $this->repository->delete($id);

        event(new WardDeletedEvent());

        return $this->success();
    }
}
