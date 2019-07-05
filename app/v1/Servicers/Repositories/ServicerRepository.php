<?php

namespace App\v1\Servicers\Repositories;

use Jsdecena\Baserepo\BaseRepository;
use App\v1\Servicers\Servicer;
use App\v1\Servicers\Exceptions\ServicerNotFoundException;
use App\v1\Servicers\Repositories\Interfaces\ServicerRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ServicerRepository extends BaseRepository implements ServicerRepositoryInterface
{
    /**
     * ServicerRepository constructor.
     *
     * @param Servicer $servicer
     */
    public function __construct(Servicer $servicer)
    {
        parent::__construct($servicer);
        $this->model = $servicer;
    }

    /**
     * List all the servicers
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection
     */
    public function listServicers(string $order = 'id', string $sort = 'desc'): Collection
    {
        return $this->all(['*'], $order, $sort);
    }

    /**
     * Create the servicer
     *
     * @param array $data
     *
     * @return Employee
     */
    public function createServicer(array $data): Servicer
    {
        $data['password'] = Hash::make($data['password']);
        return $this->create($data);
    }

    /**
     * Find the servicer by id
     *
     * @param int $id
     *
     * @return Servicer
     */
    public function findServicerById(int $id): Employee
    {
        try {
            return $this->findOneOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new EmployeeNotFoundException;
        }
    }

    /**
     * Update servicer
     *
     * @param array $params
     *
     * @return bool
     */
    public function updateServicer(array $params): bool
    {
        if (isset($params['password'])) {
            $params['password'] = Hash::make($params['password']);
        }

        return $this->update($params);
    }

    /**
     * @param array $roleIds
     */
    public function syncRoles(array $roleIds)
    {
        $this->model->roles()->sync($roleIds);
    }

    /**
     * @return Collection
     */
    public function listRoles(): Collection
    {
        return $this->model->roles()->get();
    }

    /**
     * @param string $roleName
     *
     * @return bool
     */
    public function hasRole(string $roleName): bool
    {
        return $this->model->hasRole($roleName);
    }

    /**
     * @param Servicer $servicer
     *
     * @return bool
     */
    public function isAuthUser(Servicer $servicer): bool
    {
        $isAuthUser = false;
        if (Auth::guard('servicer')->user()->id == $servicer->id) {
            $isAuthUser = true;
        }
        return $isAuthUser;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function deleteServicer() : bool
    {
        return $this->delete();
    }
}
