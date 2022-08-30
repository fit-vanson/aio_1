<?php

namespace App\Policies;

use App\Models\Dev;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DevSamsungPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.dev_samsung-index'));
    }


    public function show(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.dev_samsung-show'));
    }


    public function add(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.dev_samsung-add'));
    }


    public function edit(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.dev_samsung-edit'));
    }

    public function update(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.dev_samsung-update'));
    }

    public function delete(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.dev_samsung-delete'));
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
