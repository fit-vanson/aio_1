<?php

namespace App\Policies;

use App\Models\Dev;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DevHuaweiPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.dev_huawei-index'));
    }


    public function show(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.dev_huawei-show'));
    }


    public function add(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.dev_huawei-add'));
    }


    public function edit(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.dev_huawei-edit'));
    }

    public function update(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.dev_huawei-update'));
    }

    public function delete(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.dev_huawei-delete'));
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
