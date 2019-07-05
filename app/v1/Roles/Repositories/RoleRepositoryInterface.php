<?php

namespace App\v1\Roles\Repositories;

use Jsdecena\Baserepo\BaseRepositoryInterface;
use App\v1\Permissions\Permission;
use App\v1\Roles\Role;
use Illuminate\Support\Collection;

interface RoleRepositoryInterface extends BaseRepositoryInterface
{
    public function createRole(array $data) : Role;

    public function listRoles(string $order = 'id', string $sort = 'desc') : Collection;

    public function findRoleById(int $id);

    public function updateRole(array $data) : bool;

    public function deleteRoleById() : bool;

    public function attachToPermission(Permission $permission);

    public function attachToPermissions(... $permissions);

    public function syncPermissions(array $ids);

    public function listPermissions() : Collection;
}
