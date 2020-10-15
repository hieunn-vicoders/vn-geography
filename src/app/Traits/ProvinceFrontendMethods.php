<?php

namespace VCComponent\Laravel\Geography\Traits;

use Exception;
use Illuminate\Http\Request;
use VCComponent\Laravel\Geography\Events\ProvinceCreatedEvent;
use VCComponent\Laravel\Geography\Events\ProvinceDeletedEvent;
use VCComponent\Laravel\Geography\Events\ProvinceUpdatedEvent;
use VCComponent\Laravel\Geography\Repositories\ProvinceRepository;
use VCComponent\Laravel\Geography\Transformers\ProvinceTransformer;
use VCComponent\Laravel\Vicoders\Core\Exceptions\NotFoundException;
use VCComponent\Laravel\Vicoders\Core\Exceptions\PermissionDeniedException;

trait ProvinceFrontendMethods
{
    public function __construct(ProvinceRepository $repository, Request $request)
    {
        $this->repository = $repository;
        $this->entity     = $repository->getEntity();

        if (config('geography.auth_middleware')['frontend']['middleware']) {
            $this->middleware(
                config('geography.auth_middleware')['frontend']['middleware'],
                ['except' => config('geography.auth_middleware.frontend.except')]
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

        // $query = $this->applyQueryScope($query, 'type', $this->type);
        $query = $this->applyConstraintsFromRequest($query, $request);
        $query = $this->applySearchFromRequest($query, ['name'], $request);
        $query = $this->applyOrderByFromRequest($query, $request);

        $per_page   = $request->has('per_page') ? (int) $request->get('per_page') : 15;
        $provinces = $query->paginate($per_page);

        if ($request->has('includes')) {
            $transformer = new $this->transformer(explode(',', $request->get('includes')));
        } else {
            $transformer = new $this->transformer;
        }

        return $this->response->paginator($provinces, $transformer);
    }

    function list(Request $request) {
        $query = $this->entity;

        // $query = $this->applyQueryScope($query, 'type', $this->type);
        $query = $this->applyConstraintsFromRequest($query, $request);
        $query = $this->applySearchFromRequest($query, ['name'], $request);
        $query = $this->applyOrderByFromRequest($query, $request);

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

        return $this->response->item($province, $transformer);
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
        $province = $this->repository->create($data);

        event(new ProvinceCreatedEvent($province));

        return $this->response->item($province, new $this->transformer);
    }

    public function update(Request $request, $id)
    {
        $province = $this->repository->findWhere(['id' => $id])->first();
        if (!$province) {
            throw new NotFoundException('Province');
        }

        if (config('geography.auth_middleware')['frontend']['middleware'] !== '') {
            $user = $this->getAuthenticatedUser();
            if (!$this->entity->ableToUpdateItem($user, $id)) {
                throw new PermissionDeniedException();
            }
        }

        $data = $request->all();
        $province = $this->repository->update($data, $id);
        $province->save();


        event(new ProvinceUpdatedEvent($province));

        return $this->response->item($province, new $this->transformer);
    }

    public function destroy(Request $request, $id)
    {
        $province = $this->repository->findWhere(['id' => $id])->first();
        if (!$province) {
            throw new NotFoundException('Province');
        }

        if (config('geography.auth_middleware')['frontend']['middleware'] !== '') {
            $user = $this->getAuthenticatedUser();
            if (!$this->entity->ableToDelete($user, $id)) {
                throw new PermissionDeniedException();
            }
        }

        $this->repository->delete($id);

        event(new ProvinceDeletedEvent());

        return $this->success();
    }
}
