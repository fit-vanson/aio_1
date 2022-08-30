<?php

namespace App\Policies;

use App\Models\Template;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TemplatePolicy
{
    use HandlesAuthorization;
    public function index(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.template-index'));
    }


    public function show(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.template-show'));
    }


    public function add(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.template-add'));
    }


    public function edit(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.template-edit'));
    }

    public function update(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.template-update'));
    }

    public function delete(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.template-delete'));
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
