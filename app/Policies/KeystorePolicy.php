<?php

namespace App\Policies;

use App\Models\Template;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class KeystorePolicy
{
    use HandlesAuthorization;
    public function index(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.keystore-index'));
    }


    public function show(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.keystore-show'));
    }


    public function add(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.keystore-add'));
    }


    public function edit(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.keystore-edit'));
    }

    public function update(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.keystore-update'));
    }

    public function delete(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.keystore-delete'));
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
