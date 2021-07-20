<?php namespace Manivelle\Panneau;

use Panneau\Users\UsersServiceProvider as BaseUsersServiceProvider;

class UsersServiceProvider extends BaseUsersServiceProvider
{
    protected $resources = array(
        'users' => 'Manivelle\Panneau\Resources\UsersResource'
    );
    
    protected $resourcesControllers = array();
}
