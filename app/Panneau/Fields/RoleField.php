<?php namespace Manivelle\Panneau\Fields;

use Manivelle;
use Panneau\Support\Field;
use Manivelle\Models\Role;

class RoleField extends Field
{
    
    protected $attributes = [
        'type' => 'role'
    ];
    
    protected static $roles;
    
    protected static function getRoles()
    {
        if (!self::$roles) {
            self::$roles = Role::where('slug', 'LIKE', 'organisation.%')->get();
        }
        
        return self::$roles;
    }
    
    public function attributes()
    {
        $roles = self::getRoles();
        $options = array(
            array(
                'value' => '',
                'label' => '---'
            )
        );
        
        foreach ($roles as $role) {
            $options[] = array(
                'value' => $role->id,
                'label' => $role->name
            );
        }
        
        return [
            'values' => $options
        ];
    }
}
