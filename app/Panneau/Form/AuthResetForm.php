<?php namespace Manivelle\Panneau\Form;

use Panneau\Support\Form;
use Localizer;

class AuthResetForm extends Form
{
    protected $attributes = [
        'name' => 'auth.reset'
    ];

    public function attributes()
    {
        return [
            'action' => app('url')->route(Localizer::routeName('auth.reset')),
            'method' => 'POST'
        ];
    }

    public function fields()
    {
        return [
            [
                'type' => 'hidden',
                'name' => 'token',
                'value' => $this->request->route('token'),
            ],
            [
                'type' => 'Text',
                'name' => 'email',
                'value' => $this->request->old('email', $this->request->get('email')),
                'label' => trans('panneau::forms.label_email')
            ],
            [
                'type' => 'Text',
                'name' => 'password',
                'label' => trans('panneau::forms.label_password'),
                'attributes' => [
                    'type' => 'password'
                ]
            ],
            [
                'type' => 'Text',
                'name' => 'password_confirmation',
                'label' => trans('panneau::forms.label_password_confirmation'),
                'attributes' => [
                    'type' => 'password'
                ]
            ]
        ];
    }

    public function buttons()
    {
        return array(
            array(
                'name' => 'submit',
                'label' => trans('general.actions.reset_password'),
                'type' => 'submit'
            )
        );
    }

    public function rules()
    {
        return [
            'email' => 'required',
            'password' => 'required|confirmed|min:6',
        ];
    }
}
