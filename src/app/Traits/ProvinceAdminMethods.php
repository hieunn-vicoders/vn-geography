<?php

namespace VCComponent\Laravel\Geography\Traits;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use VCComponent\Laravel\Geography\Events\ProvinceCreatedByAdminEvent;
use VCComponent\Laravel\Geography\Events\ProvinceUpdatedByAdminEvent;
use VCComponent\Laravel\Geography\Events\ProvinceDeletedEvent;
use VCComponent\Laravel\Geography\Repositories\ProvinceRepository;
use VCComponent\Laravel\Geography\Transformers\ProvinceTransformer;
use VCComponent\Laravel\Vicoders\Core\Exceptions\NotFoundException;
use VCComponent\Laravel\Vicoders\Core\Exceptions\PermissionDeniedException;

trait ProvinceAdminMethods
{
    public function __construct(ProvinceRepository $repository, Request $request)
    {
        $this->repository = $repository;
        $this->entity     = $repository->getEntity();

        if (config('geography.auth_middleware')['admin']['middleware']) {
            $this->middleware(
                config('geography.auth_middleware')['admin']['middleware'],
                ['except' => config('geography.auth_middleware.admin.except')]
            );
        }

        if (isset(config('geography.transformers')['province'])) {
            $this->transformer = config('geography.transformers.province');
        } else {
            $this->transformer = ProvinceTransformer::class;
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

        $per_page  = $request->has('per_page') ? (int) $request->get('per_page') : 15;
        $provinces = $query->paginate($per_page);

        if ($request->has('includes')) {
            $transformer = new $this->transformer(explode(',', $request->get('includes')));
        } else {
            $transformer = new $this->transformer;
        }

        return $this->response->paginator($provinces, $transformer);
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

        $provinces = $query->get();

        if ($request->has('includes')) {
            $transformer = new $this->transformer(explode(',', $request->get('includes')));
        } else {
            $transformer = new $this->transformer;
        }

        return $this->response->collection($provinces, $transformer);
    }

    public function show(Request $request, $id)
    {
        $province = $this->repository->findWhere(['id' => $id])->first();
        if (!$province) {
            throw new NotFoundException('Province');
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

        return $this->response->item($province, $transformer);
    }

    public function store(Request $request)
    {
        if (config('geography.auth_middleware')['admin']['middleware']) {
            $user = $this->getAuthenticatedUser();
            if (!$this->entity->ableToCreate($user)) {
                throw new PermissionDeniedException();
            }
        }

        $data     = $request->all();
        $province = $this->repository->create($data);

        event(new ProvinceCreatedByAdminEvent($province));

        return $this->response->item($province, new $this->transformer);
    }

    public function update(Request $request, $id)
    {
        $province = $this->repository->findWhere(['id' => $id])->first();
        if (!$province) {
            throw new NotFoundException('Province');
        }

        if (config('geography.auth_middleware')['admin']['middleware']) {
            $user = $this->getAuthenticatedUser();
            if (!$this->entity->ableToUpdateItem($user, $id)) {
                throw new PermissionDeniedException();
            }
        }

        $data     = $request->all();
        $province = $this->repository->update($data, $id);
        $province->save();

        event(new ProvinceUpdatedByAdminEvent($province));

        return $this->response->item($province, new $this->transformer);
    }

    public function destroy(Request $request, $id)
    {
        $province = $this->repository->findWhere(['id' => $id])->first();
        if (!$province) {
            throw new NotFoundException('Province');
        }

        if (config('geography.auth_middleware')['admin']['middleware']) {
            $user = $this->getAuthenticatedUser();
            if (!$this->entity->ableToDelete($user, $id)) {
                throw new PermissionDeniedException();
            }
        }

        $this->repository->delete($id);

        event(new ProvinceDeletedEvent());

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

        $province = $this->repository->findWhere(['id' => $id])->first();
        if (!$province) {
            throw new NotFoundException('Province');
        }

        $data             = $request->all('status');
        $province->status = $data['status'];
        $province->save();

        return $this->success();
    }
}
