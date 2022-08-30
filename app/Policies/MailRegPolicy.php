<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MailRegPolicy
{
    use HandlesAuthorization;


    public function index(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.mail_reg-index'));
    }


    public function show(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.mail_reg-show'));
    }


    public function add(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.mail_reg-add'));
    }


    public function edit(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.mail_reg-edit'));
    }

    public function update(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.mail_reg-update'));
    }

    public function delete(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.mail_reg-delete'));
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
