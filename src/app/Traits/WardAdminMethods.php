<?php

namespace VCComponent\Laravel\Geography\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use VCComponent\Laravel\Geography\Events\WardCreatedByAdminEvent;
use VCComponent\Laravel\Geography\Events\WardDeletedEvent;
use VCComponent\Laravel\Geography\Events\WardUpdatedByAdminEvent;
use VCComponent\Laravel\Geography\Repositories\DistrictRepository;
use VCComponent\Laravel\Geography\Transformers\WardTransformer;
use VCComponent\Laravel\Vicoders\Core\Exceptions\NotFoundException;
use VCComponent\Laravel\Vicoders\Core\Exceptions\PermissionDeniedException;

trait WardAdminMethods
{
    public function __construct(DistrictRepository $repository, Request $request)
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

        // $query = $this->applyQueryScope($query, 'type', $this->type);
        $query = $this->applyConstraintsFromRequest($query, $request);
        $query = $this->applySearchFromRequest($query, ['name'], $request);
        $query = $this->applyOrderByFromRequest($query, $request);


        if ($request->has('type')) {
            $query = $query->where('type', $request->get('type'));
        }
        if ($request->has('status')) {
            $query = $query->where('status', $request->get('status'));
        }

        $per_page   = $request->has('per_page') ? (int) $request->get('per_page') : 15;
        $wards = $query->paginate($per_page);

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
            throw new Exception("Phường hoặc xã này không tồn tại", 1);
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
        $data         = $request->all();
        $data['slug'] = Str::slug($data['name']);
        $ward     = $this->repository->findWhere(['name' => $data['name'], 'type' => $data['type']])->first();
        if ($ward) {
            throw new \Exception("", 1);
        }
        $this->validator->isValid($data, 'RULE_ADMIN_CREATE');

        $ward = $this->repository->create($data);
        $ward->save();

        event(new WardCreatedByAdminEvent($ward));

        return $this->response->item($ward, new $this->transformer);
    }

    public function update(Request $request, $id)
    {
        $ward = $this->repository->findWhere(['id' => $id])->first();
        if (!$ward) {
            throw new Exception("Phường hoặc xã này không tồn tại", 1);
        }

        if (config('geography.auth_middleware')['admin']['middleware']) {
            $user = $this->getAuthenticatedUser();
            if (!$this->entity->ableToUpdateItem($user, $id)) {
                throw new PermissionDeniedException();
            }
        }

        $data         = $request->all();
        $data['slug'] = Str::slug($data['name']);
        $province     = $this->entity->where('id', '<>', $id)
            ->where('name', $data['name'])
            ->where('type', $data['type'])
            ->first();
        if ($province) {
            throw new \Exception("", 1);
        }
        $this->validator->isValid($data, 'RULE_ADMIN_UPDATE');

        $ward = $this->repository->update($data, $id);

        if ($request->has('status')) {
            $ward->status = $request->get('status');
            $ward->save();
        }

        event(new WardUpdatedByAdminEvent($province));

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
}
