<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class KhosimPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.khosim-index'));
    }


    public function show(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.khosim-show'));
    }


    public function add(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.khosim-add'));
    }


    public function edit(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.khosim-edit'))
            ? Response::allow()
            : Response::deny('Tài khoản không có quyền chỉnh sửa.');
    }

    public function update(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.khosim-update'));
    }

    public function delete(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.khosim-delete'));
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
