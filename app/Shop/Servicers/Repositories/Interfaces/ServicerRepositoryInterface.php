<?php

namespace App\Shop\Servicers\Repositories\Interfaces;

use Jsdecena\Baserepo\BaseRepositoryInterface;
use App\Shop\Servicers\Servicer;
use Illuminate\Support\Collection;

interface ServicerRepositoryInterface extends BaseRepositoryInterface
{
    public function listServicers(string $order = 'id', string $sort = 'desc'): Collection;

    public function createServicer(array $params) : Servicer;

    public function findServicerById(int $id) : Servicer;

    public function updateServicer(array $params): bool;

    public function syncRoles(array $roleIds);

    public function listRoles() : Collection;

    public function hasRole(string $roleName) : bool;

    public function isAuthUser(Servicer $servicer): bool;

    public function deleteServicer() : bool;
}
