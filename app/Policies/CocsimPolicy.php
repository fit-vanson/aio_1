<?php

namespace App\Policies;

use App\Models\User;
use App\Models\cocsim;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CocsimPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.cocsim-index'));
    }


    public function show(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.cocsim-show'));
    }


    public function add(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.cocsim-add'));
    }


    public function edit(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.cocsim-edit'))
            ? Response::allow()
            : Response::deny('Tài khoản không có quyền chỉnh sửa.');
    }

    public function update(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.cocsim-update'));
    }

    public function delete(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.cocsim-delete'));
    }


    public function restore(User $user)
    {
        //
    }

    public function forceDelete(User $user)
    {
        //
    }
}
