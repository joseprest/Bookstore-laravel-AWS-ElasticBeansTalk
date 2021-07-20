<?php namespace Manivelle\Panneau\Form;

use Panneau\Support\Form;
use Localizer;
use Request;

class ScreenLinkForm extends Form
{
    protected $attributes = [
        'name' => 'organisation.screens.link'
    ];
    
    public function attributes()
    {
        $organisation = Request::route('organisation');
        return [
            'action' => route(Localizer::routeName('organisation.screens.link'), array($organisation->slug)),
            'method' => 'PUT'
        ];
    }
    
    public function buttons()
    {
        return [
            [
                'className' => 'btn btn-default',
                'type' => 'submit',
                'label' => trans('general.actions.continue')
            ]
        ];
    }
    
    public function fields()
    {
        return [
            [
                'type' => 'text',
                'name' => 'auth_code',
                'label' => trans('screen.inputs.auth_code')
            ]
        ];
    }
}
