<?php namespace Manivelle\Panneau\Form;

use Panneau\Support\Form;
use Localizer;

class TeamInviteForm extends Form
{
    protected $attributes = [
        'name' => 'organisation.team.invite'
    ];
    
    public function attributes()
    {
        $organisation = $this->request->route('organisation');
        return [
            'action' => route(Localizer::routeName('organisation.team.invite'), array($organisation->slug)),
            'method' => 'PUT'
        ];
    }
    
    public function fields()
    {
        
        
        return [
            [
                'type' => 'text',
                'name' => 'email',
                'label' => trans('team.inputs.email')
            ],
            [
                'type' => 'role',
                'name' => 'role_id',
                'label' => trans('team.inputs.role')
            ]
        ];
    }
    
    public function buttons()
    {
        return [
            [
                'className' => 'btn btn-default',
                'type' => 'submit',
                'label' => trans('invitation.actions.send')
            ]
        ];
    }
}
