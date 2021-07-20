<?php namespace Manivelle\Panneau\Form;

use Panneau\Support\Form;
use Localizer;

class AuthResetEmailForm extends Form
{
    protected $attributes = [
        'name' => 'auth.reset.email'
    ];

    public function attributes()
    {
        return [
            'action' => app('url')->route(Localizer::routeName('auth.reset.email')),
            'method' => 'POST'
        ];
    }

    public function fields()
    {
        return [
            [
                'type' => 'Text',
                'name' => 'email',
                'label' => trans('panneau::forms.label_email')
            ]
        ];
    }

    public function buttons()
    {
        return array(
            array(
                'name' => 'submit',
                'label' => trans('general.actions.send_link'),
                'type' => 'submit'
            )
        );
    }

    public function rules()
    {
        return [
            'email' => 'required',
        ];
    }
}
