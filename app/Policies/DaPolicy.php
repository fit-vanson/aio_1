<?php

namespace App\Policies;

use App\Models\Da;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DaPolicy
{
    use HandlesAuthorization;


    public function index(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.du_an-index'));
    }


    public function show(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.du_an-show'));
    }


    public function add(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.du_an-add'));
    }


    public function edit(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.du_an-edit'));
    }

    public function update(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.du_an-update'));
    }

    public function delete(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.du_an-delete'));
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
