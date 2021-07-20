<?php namespace Manivelle\Panneau\Form;

use Panneau\Support\Form;
use Localizer;

class TeamForm extends Form
{
    protected $attributes = [
        'name' => 'organisation.team'
    ];
    
    public function attributes()
    {
        $organisation = $this->request->route('organisation');
        
        return [
            'action' => route(Localizer::routeName('organisation.team.update'), [$organisation->url, $this->model->id]),
            'method' => 'POST'
        ];
    }
    
    public function fields()
    {
        return [
            
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
