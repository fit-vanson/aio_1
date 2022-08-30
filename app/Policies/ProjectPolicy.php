<?php

namespace App\Policies;

use App\Models\ProjectModel;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function index(User $user)
    {
         return $user->checkPermissionAccess(config('permissions.access.project-index'));
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProjectModel  $projectModel
     * @return mixed
     */
    public function show(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.project-show'));
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function add(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.project-add'));
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProjectModel  $projectModel
     * @return mixed
     */
    public function edit(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.project-edit'));
    }

    public function update(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.project-update'));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProjectModel  $projectModel
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->checkPermissionAccess(config('permissions.access.project-delete'));
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProjectModel  $projectModel
     * @return mixed
     */
    public function restore(User $user, ProjectModel $projectModel)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProjectModel  $projectModel
     * @return mixed
     */
    public function forceDelete(User $user, ProjectModel $projectModel)
    {
        //
    }
}
