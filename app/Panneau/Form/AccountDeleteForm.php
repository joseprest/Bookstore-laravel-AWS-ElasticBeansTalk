<?php namespace Manivelle\Panneau\Form;

use Panneau\Support\Form as BaseForm;
use Localizer;
use Request;

class AccountDeleteForm extends BaseForm
{
    protected $attributes = [
        'name' => 'account.delete'
    ];
    
    public function attributes()
    {
        return [
            'action' => Localizer::route('account.delete'),
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
