<?php namespace Manivelle\Panneau\Form;

use Panneau\Support\Form as BaseForm;
use Localizer;
use App;

class AccountForm extends BaseForm
{
    protected $attributes = [
        'name' => 'account'
    ];
    
    public function attributes()
    {
        return [
            'action' => Localizer::route('account.update'),
            'method' => 'post',
            'token' => csrf_token()
        ];
    }
    
    public function fields()
    {
        // Builds a list of locales shown as
        // '{locale name} ({translated locale name})'
        $localesSelectValues = array_map(
            function ($locale) {
                $localeName = config('locale.localeNames.' . $locale, $locale);
                $localizedLocaleName = trans('locales.' . $locale);

                if ($locale != App::getLocale()) {
                    $localeName .= ' (' . $localizedLocaleName . ')';
                }

                return [
                    'value' => $locale,
                    'label' => $localeName
                ];
            },
            Localizer::getAllLocales()
        );

        return [
            [
                'name' => 'name',
                'type' => 'text',
                'label' => trans('user.inputs.name')
            ],
            [
                'name' => 'email',
                'type' => 'text',
                'label' => trans('user.inputs.email')
            ],
            [
                'name' => 'locale',
                'type' => 'select',
                'label' => trans('user.inputs.locale'),
                'values' => $localesSelectValues
            ],
            [
                'type' => 'fieldset',
                'legend' => trans('user.inputs.security'),
                'children' => [
                    [
                        'name' => 'password',
                        'type' => 'password_change',
                        'label' => trans('user.inputs.password')
                    ],
                ]
            ],
            [
                'type' => 'fieldset',
                'legend' => trans('user.inputs.organisations'),
                'children' => [
                    [
                        'name' => 'organisations',
                        'type' => 'organisations'
                    ]
                ]
            ]
        ];
    }
    
    public function buttons()
    {
        return [
            [
                'className' => 'btn btn-primary btn-lg',
                'type' => 'submit',
                'label' => trans('general.actions.save')
            ]
        ];
    }

    public function getData()
    {
        $data = parent::getData();

        /*
         * To display the organisations list, the HTML requires full
         * Organisation objects (with names, slug, ...). But the generated form
         * will only submit their ids. So if an error occurs, the form is
         * redisplayed with the values from the POST request, so the
         * organisations list becomes only a list of ids. So a bug is created
         * where we don't see the names of the organisations.
         *
         * Here, we ensure the list constains the full objects.
         */
        if (array_key_exists('organisations', $data)) {
            $organisations = $data['organisations'];

            if (count($organisations) && ! is_array($organisations[0])) {
                $ids = $organisations;
                $newOrganisations = [];
                $allUserOrganisations = $this->model->organisations;

                foreach ($allUserOrganisations as $userOrganisation) {
                    if (in_array($userOrganisation->id, $ids)) {
                        $newOrganisations[] = $userOrganisation;
                    }
                }

                $data['organisations'] = $newOrganisations;
            }
        }

        return $data;
    }
}
