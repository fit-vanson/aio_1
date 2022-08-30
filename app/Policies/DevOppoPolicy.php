<?php

namespace App\Policies;

use App\Models\Dev;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DevOppoPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.dev_oppo-index'));
    }


    public function show(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.dev_oppo-show'));
    }


    public function add(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.dev_oppo-add'));
    }


    public function edit(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.dev_oppo-edit'));
    }

    public function update(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.dev_oppo-update'));
    }

    public function delete(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.dev_oppo-delete'));
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
