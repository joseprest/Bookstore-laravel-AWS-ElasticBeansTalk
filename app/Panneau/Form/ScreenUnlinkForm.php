<?php namespace Manivelle\Panneau\Form;

use Panneau\Support\Form;
use Localizer;
use Request;

class ScreenUnlinkForm extends Form
{
    protected $attributes = [
        'name' => 'screen.unlink'
    ];
    
    public function attributes()
    {
        $organisation = Request::route('organisation');
        return [
            'action' => route(Localizer::routeName('organisation.screens.unlink'), array($organisation->slug, $this->model->id)),
            'method' => 'delete',
            'token' => csrf_token()
        ];
    }
    
    public function buttons()
    {
        return [
            [
                'className' => 'btn btn-danger',
                'type' => 'submit',
                'label' => trans('general.actions.delete')
            ]
        ];
    }
}
