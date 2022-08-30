<?php

namespace App\Policies;


use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GaPolicy
{
    use HandlesAuthorization;
    public function index(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.ga-index'));
    }


    public function show(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.ga-show'));
    }


    public function add(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.ga-add'));
    }


    public function edit(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.ga-edit'));
    }

    public function update(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.ga-update'));
    }

    public function delete(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.ga-delete'));
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
