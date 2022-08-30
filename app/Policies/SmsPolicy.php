<?php

namespace App\Policies;

use App\Models\User;
use App\Models\sms;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class SmsPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.sms-index'));
    }


    public function show(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.sms-show'));
    }


    public function add(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.sms-add'));
    }


    public function edit(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.sms-edit'))
            ? Response::allow()
            : Response::deny('Tài khoản không có quyền chỉnh sửa.');
    }

    public function update(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.sms-update'));
    }

    public function delete(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.sms-delete'));
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
