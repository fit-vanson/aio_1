<?php

namespace App\Policies;

use App\Models\Dev;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DevPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.dev-index'));
    }


    public function show(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.dev-show'));
    }


    public function add(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.dev-add'));
    }


    public function edit(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.dev-edit'));
    }

    public function update(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.dev-update'));
    }

    public function delete(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.dev-delete'));
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
