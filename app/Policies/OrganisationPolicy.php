<?php

namespace Manivelle\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use Manivelle\User;
use Manivelle\Models\Organisation;

class OrganisationPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function before($user, $ability)
    {
        if ($user->is('admin')) {
            return true;
        }
    }

    public function view(User $user, Organisation $organisation)
    {
        $role = $organisation->getUserRole($user);

        if (!$role) {
            return false;
        }

        return true;
    }

    public function edit(User $user, Organisation $organisation)
    {
        $role = $organisation->getUserRole($user);

        if (!$role || !$role->is('admin')) {
            return false;
        }

        return true;
    }

    public function teamManage(User $user, Organisation $organisation)
    {
        $role = $organisation->getUserRole($user);

        if (!$role || !$role->is('admin')) {
            return false;
        }

        return true;
    }

    public function screenCreate(User $user, Organisation $organisation)
    {
        $role = $organisation->getUserRole($user);

        if (!$role || !$role->is('admin')) {
            return false;
        }

        return true;
    }

    public function screenAdd(User $user, Organisation $organisation)
    {
        $role = $organisation->getUserRole($user);

        if (!$role || !$role->is('admin')) {
            return false;
        }

        return true;
    }

    public function screenManageChannels(User $user, Organisation $organisation)
    {
        $role = $organisation->getUserRole($user);

        if (!$role || !$role->is('admin')) {
            return false;
        }

        return true;
    }

    public function screenViewControls(User $user, Organisation $organisation)
    {
        if (!$user->is('admin')) {
            return false;
        }

        return true;
    }

    public function screenViewSettings(User $user, Organisation $organisation)
    {
        $role = $organisation->getUserRole($user);

        if (!$role || !$role->is('admin')) {
            return false;
        }

        return true;
    }
}
