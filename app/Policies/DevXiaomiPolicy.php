<?php

namespace App\Policies;

use App\Models\Dev;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DevXiaomiPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.dev_xiaomi-index'));
    }


    public function show(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.dev_xiaomi-show'));
    }


    public function add(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.dev_xiaomi-add'));
    }


    public function edit(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.dev_xiaomi-edit'));
    }

    public function update(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.dev_xiaomi-update'));
    }

    public function delete(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.dev_xiaomi-delete'));
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
