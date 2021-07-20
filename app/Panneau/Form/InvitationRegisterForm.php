<?php namespace Manivelle\Panneau\Form;

use Panneau\Support\Form;
use Localizer;
use App;
use Log;

class InvitationRegisterForm extends Form
{
    protected $attributes = [
        'name' => 'invitation.register'
    ];
    
    public function attributes()
    {
        $invitation = $this->model;
        $organisation = $invitation->organisation;
        return [
            'action' => route(
                Localizer::routeName('organisation.invitation.store'),
                [$organisation->slug, $invitation->invitation_key]
            ),
            'method' => 'PUT'
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

        // Sort the array to have the current locale first
        usort($localesSelectValues, function ($locale1, $locale2) {
            $currentLocale = App::getLocale();

            if ($locale1['value'] == $currentLocale) {
                return -1;
            }

            if ($locale2['value'] == $currentLocale) {
                return 1;
            }

            return 0;
        });

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
                'values' => $localesSelectValues,
            ],
            [
                'name' => 'password',
                'type' => 'password',
                'label' => trans('user.inputs.password'),
                'confirmationLabel' => trans('user.inputs.confirmPassword')
            ]
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
    
    public function render()
    {
        try {
            return parent::render();
        } catch (\Exception $e) {
            Log::error($e);
            throw $e;
        }
    }
}
