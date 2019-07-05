<?php

namespace App\Http\Controllers\Servicer\Permissions;

use App\Http\Controllers\Controller;
use App\Shop\Permissions\Repositories\PermissionRepository;

class PermissionController extends Controller
{
    /**
     * @var PermissionRepository
     */
    private $permRepo;

    /**
     * PermissionController constructor.
     *
     * @param PermissionRepository $permissionRepository
     */
    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permRepo = $permissionRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $list = $this->permRepo->listPermissions();

        $permissions = $this->permRepo->paginateArrayResults($list->all());

        return view('servicer.permissions.list', compact('permissions'));
    }
}
