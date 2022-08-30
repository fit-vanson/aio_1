<?php

namespace App\Policies;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ScriptPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.script-index'));
    }


    public function show(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.script-show'));
    }


    public function add(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.script-add'));
    }


    public function edit(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.script-edit'));
    }

    public function update(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.script-update'));
    }

    public function delete(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.script-delete'));
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
