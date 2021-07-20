<?php namespace Manivelle\Models;

use Panneau\Models\Role as BaseRole;

class Role extends BaseRole
{
    protected function getNameAttribute($value)
    {
        return trans('user.roles.'.$value);
    }
    
    public function is($key)
    {
        return preg_match('/'.$key.'$/', $this->slug);
    }
}
