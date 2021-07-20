<?php

namespace Manivelle\Http\Controllers\Admin;

use Auth;
use Illuminate\Http\Request;
use Panneau\Users\UsersController as BaseUsersController;

class UsersController extends BaseUsersController
{
    protected $views = array(
        'index' => 'admin.users.index'
    );

    protected function getItems($query = [], $page = null)
    {
        $items = $this->resource->get(function ($query) {
            $user = Auth::user();
            if ($user->organisation_id) {
                $query->where('organisation_id', $user->organisation_id);
            }
        });
        
        return $items;
    }
}
