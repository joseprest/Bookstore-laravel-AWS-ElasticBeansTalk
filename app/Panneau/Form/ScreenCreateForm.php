<?php namespace Manivelle\Panneau\Form;

use Panneau\Support\Form;
use Localizer;
use Request;

class ScreenCreateForm extends Form
{
    protected $attributes = [
        'name' => 'organisation.screens.create'
    ];

    public function attributes()
    {
        $organisation = Request::route('organisation');
        return [
            'action' => route(Localizer::routeName('organisation.screens.store'), array($organisation->slug)),
            'method' => 'POST'
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
                'name' => 'name',
                'label' => trans('screen.inputs.name')
            ]
        ];
    }
}
