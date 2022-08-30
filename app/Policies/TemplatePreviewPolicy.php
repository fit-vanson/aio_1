<?php

namespace App\Policies;

use App\Models\Template;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TemplatePreviewPolicy
{
    use HandlesAuthorization;
    public function index(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.template-preview-index'));
    }


    public function show(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.template-preview-show'));
    }


    public function add(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.template-preview-add'));
    }


    public function edit(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.template-preview-edit'));
    }

    public function update(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.template-preview-update'));
    }

    public function delete(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.template-preview-delete'));
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
