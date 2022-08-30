<?php

namespace App\Policies;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DevicePolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.device-index'));
    }


    public function show(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.device-show'));
    }


    public function add(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.device-add'));
    }


    public function edit(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.device-edit'));
    }

    public function update(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.device-update'));
    }

    public function delete(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.device-delete'));
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
