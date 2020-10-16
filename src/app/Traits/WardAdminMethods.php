<?php

namespace VCComponent\Laravel\Geography\Traits;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use VCComponent\Laravel\Geography\Events\WardCreatedByAdminEvent;
use VCComponent\Laravel\Geography\Events\WardDeletedEvent;
use VCComponent\Laravel\Geography\Events\WardUpdatedByAdminEvent;
use VCComponent\Laravel\Geography\Repositories\WardRepository;
use VCComponent\Laravel\Geography\Transformers\WardTransformer;
use VCComponent\Laravel\Vicoders\Core\Exceptions\NotFoundException;
use VCComponent\Laravel\Vicoders\Core\Exceptions\PermissionDeniedException;

trait WardAdminMethods
{
    public function __construct(WardRepository $repository, Request $request)
    {
        $this->repository = $repository;
        $this->entity     = $repository->getEntity();

        if (config('geography.auth_middleware')['admin']['middleware']) {
            $this->middleware(
                config('geography.auth_middleware')['admin']['middleware'],
                ['except' => config('geography.auth_middleware.admin.except')]
            );
        }

        if (isset(config('geography.transformers')['ward'])) {
            $this->transformer = config('geography.transformers.ward');
        } else {
            $this->transformer = WardTransformer::class;
        }
    }

    public function index(Request $request)
    {
        $query = $this->entity;
        $query = $this->applyConstraintsFromRequest($query, $request);
        $query = $this->applySearchFromRequest($query, ['name'], $request);
        $query = $this->applyOrderByFromRequest($query, $request);


        if ($request->has('type')) {
            $query = $query->where('type', $request->get('type'));
        }
        if ($request->has('status')) {
            $query = $query->where('status', $request->get('status'));
        }

        $per_page = $request->has('per_page') ? (int) $request->get('per_page') : 15;
        $wards    = $query->paginate($per_page);

        if ($request->has('includes')) {
            $transformer = new $this->transformer(explode(',', $request->get('includes')));
        } else {
            $transformer = new $this->transformer;
        }

        return $this->response->paginator($wards, $transformer);
    }

    function list(Request $request)
    {
        $query = $this->entity;
        $query = $this->applyConstraintsFromRequest($query, $request);
        $query = $this->applySearchFromRequest($query, ['name'], $request);
        $query = $this->applyOrderByFromRequest($query, $request);

        if ($request->has('type')) {
            $query = $query->where('type', $request->get('type'));
        }
        if ($request->has('status')) {
            $query = $query->where('status', $request->get('status'));
        }

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
            throw new NotFoundException('Ward');
        }

        if (config('geography.auth_middleware')['admin']['middleware']) {
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
        if (config('geography.auth_middleware')['admin']['middleware']) {
            $user = $this->getAuthenticatedUser();
            if (!$this->entity->ableToCreate($user)) {
                throw new PermissionDeniedException();
            }
        }

        $data = $request->all();
        $ward = $this->repository->create($data);

        event(new WardCreatedByAdminEvent($ward));

        return $this->response->item($ward, new $this->transformer);
    }

    public function update(Request $request, $id)
    {
        $ward = $this->repository->findWhere(['id' => $id])->first();
        if (!$ward) {
            throw new NotFoundException('Ward');
        }

        if (config('geography.auth_middleware')['admin']['middleware']) {
            $user = $this->getAuthenticatedUser();
            if (!$this->entity->ableToUpdateItem($user, $id)) {
                throw new PermissionDeniedException();
            }
        }

        $data = $request->all();
        $ward = $this->repository->update($data, $id);
        $ward->save();

        event(new WardUpdatedByAdminEvent($ward));

        return $this->response->item($ward, new $this->transformer);
    }

    public function destroy(Request $request, $id)
    {
        $ward = $this->repository->findWhere(['id' => $id])->first();
        if (!$ward) {
            throw new NotFoundException('Ward');
        }

        if (config('geography.auth_middleware')['admin']['middleware']) {
            $user = $this->getAuthenticatedUser();
            if (!$this->entity->ableToDelete($user, $id)) {
                throw new PermissionDeniedException();
            }
        }

        $this->repository->delete($id);

        event(new WardDeletedEvent());

        return $this->success();
    }

    public function status(Request $request, $id)
    {
        if (config('geography.auth_middleware')['admin']['middleware']) {
            $user = $this->getAuthenticatedUser();
            if (!$this->entity->ableToUpdateItem($user, $id)) {
                throw new PermissionDeniedException();
            }
        }

        $ward = $this->repository->findWhere(['id' => $id])->first();
        if (!$ward) {
            throw new NotFoundException('Ward');
        }

        $data         = $request->all('status');
        $ward->status = $data['status'];
        $ward->save();

        return $this->success();
    }
}
