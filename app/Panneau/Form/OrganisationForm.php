<?php namespace Manivelle\Panneau\Form;

use Panneau\Support\Form;
use Localizer;

class OrganisationForm extends Form
{
    protected $attributes = [
        'name' => 'organisation'
    ];

    public function attributes()
    {
        $action = null;
        if ($organisation = $this->request->route('organisation')) {
            $action = route(Localizer::routeName('organisation.update'), [$organisation->slug]);
        } elseif ($this->model) {
            $action = route(Localizer::routeName('admin.organisations.update'), [$organisation->id]);
        } else {
            $action = route(Localizer::routeName('admin.organisations.store'));
        }

        return [
            'action' => $action,
            'method' => 'POST'
        ];
    }

    public function fields()
    {
        return array(
            array(
                'name' => 'name',
                'type' => 'text',
                'label' => trans('organisation.inputs.name')
            ),
            array(
                'name' => 'slug',
                'type' => 'text',
                'label' => trans('organisation.inputs.slug'),
                'prefix' => 'http://',
                'suffix' => str_replace('{organisation}', '', config('app.domains.organisation'))
            ),
            array(
                'type' => 'fieldset',
                'legend' => trans('organisation.inputs.emails.settings'),
                'namespace' => 'settings',
                'children' => [
                    array(
                        'name' => 'email_from_name',
                        'type' => 'text',
                        'label' => trans('organisation.inputs.emails.from_name')
                    ),
                    array(
                        'name' => 'email_from',
                        'type' => 'text',
                        'label' => trans('organisation.inputs.emails.from')
                    ),
                    array(
                        'name' => 'email_reply_to',
                        'type' => 'text',
                        'label' => trans('organisation.inputs.emails.reply_to')
                    ),
                    array(
                        'name' => 'email_smtp',
                        'type' => 'text',
                        'label' => trans('organisation.inputs.emails.smtp')
                    ),
                    array(
                        'name' => 'email_subject',
                        'type' => 'text',
                        'label' => trans('organisation.inputs.emails.subject')
                    )
                ]
            ),
            array(
                'type' => 'fieldset',
                'legend' => trans('organisation.inputs.sms.settings'),
                'namespace' => 'settings',
                'children' => [
                    array(
                        'name' => 'sms_body',
                        'type' => 'text',
                        'label' => trans('organisation.inputs.sms.body')
                    )
                ]
            ),
            array(
                'type' => 'fieldset',
                'legend' => trans('organisation.inputs.channel.settings'),
                'namespace' => 'settings',
                'children' => [
                    array(
                        'name' => 'pretnumerique_id',
                        'type' => 'text',
                        'label' => trans('organisation.inputs.channel.pretnumerique_id')
                    )
                ]
            )
        );
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

    public function rules()
    {
        $uniqueSlugRule = 'unique:organisations,slug';

        // When checking if the slug is unique, we want to ignore
        // the current organisation (in $this->model, if set)
        if ($this->model && $this->model->id) {
            $uniqueSlugRule .= ',' . $this->model->id;
        }

        return [
            'name' => ['required'],
            'slug' => [$uniqueSlugRule],
            'settings.email_reply_to' => ['email'],
            'settings.email_from' => ['email']
        ];
    }
}
