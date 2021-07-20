<?php namespace Manivelle\Panneau\Form;

use Panneau\Support\Form;

class TeamInvitationForm extends Form
{
    protected $attributes = [
        'name' => 'organisation.team.invitation'
    ];
    
    public function fields()
    {
        return [
            [
                'type' => 'role',
                'name' => 'role_id',
                'label' => trans('team.inputs.modify_role')
            ]
        ];
    }
    
    public function buttons()
    {
        return [
            [
                'className' => 'btn btn-default',
                'type' => 'submit',
                'label' => trans('general.actions.save')
            ]
        ];
    }
}
