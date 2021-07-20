<?php namespace Manivelle\Panneau\Form;

use Panneau\Support\Form;
use Localizer;
use Request;

class ScreenSettingsForm extends Form
{
    protected $attributes = [
        'name' => 'screen.settings'
    ];

    public function attributes()
    {
        $organisation = Request::route('organisation');
        return [
            'action' => route(Localizer::routeName('organisation.screens.update'), array($organisation->slug, $this->model->id)),
            'method' => 'PUT',
            'token' => csrf_token()
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

    public function fields()
    {
        $views = [
            [
                'value' => 'default',
                'label' => trans('screen.inputs.start_view.default')
            ]
        ];

        if ($this->model) {
            $this->model->loadIfNotLoaded('channels');
            foreach ($this->model->channels as $channel) {
                $channelType = $channel->getChannelType();
                $channelViews = $channelType->getViews();
                foreach ($channelViews as $view) {
                    $key = 'channel:'.$channel->id.':'.$view['key'];
                    $views[$key] = [
                        'value' => $key,
                        'label' => $channel->snippet->title.' » '.$view['label']
                    ];
                }
            }
        }
        $views = array_values($views);

        // Builds a list of locales for the interface language
        $localesSelectValues = array_map(
            function ($locale) {
                return [
                    'value' => $locale,
                    'label' => trans('locales.' . $locale)
                ];
            },
            Localizer::getScreensLocales()
        );

        return [
            [
                'type' => 'text',
                'name' => 'name',
                'label' => trans('screen.inputs.name')
            ],
            [
                'type' => 'fieldset',
                'legend' => trans('screen.inputs.location'),
                'namespace' => 'fields',
                'children' => [
                    [
                        'name' => 'location',
                        'type' => 'location'
                    ]
                ]
            ],
            [
                'type' => 'fieldset',
                'legend' => trans('screen.inputs.technical_info'),
                'children' => [
                    [
                        'name' => 'technical',
                        'type' => 'screen_technical',
                        'namespace' => 'fields'
                    ],
                    [
                        'type' => 'text',
                        'name' => 'slug',
                        'label' => trans('screen.inputs.slug'),
                        'prefix' => 'http://',
                        'suffix' => preg_replace('/\{\s*screen\_uuid\\s*}/', '', config('app.domains.screen')),
                        'helpText' => trans(
                            'screen.inputs.permalink',
                            ['url' => route('screen.home', [$this->model->uuid])]
                        )
                    ]
                ]
            ],
            [
                'type' => 'fieldset',
                'legend' => trans('screen.inputs.display_settings'),
                'namespace' => 'settings',
                'children' => [

                    // Disabled until the refactor of the frontend
                    /*[
                        'name' => 'startView',
                        'type' => 'select',
                        'label' => trans('screen.inputs.start_view.label'),
                        'values' => $views
                    ],*/
                    [
                        'name' => 'countryCode',
                        'type' => 'text',
                        'label' => trans('screen.inputs.country_code')
                    ],
                    [
                        'name' => 'theme',
                        'type' => 'select',
                        'label' => trans('screen.inputs.theme'),
                        'values' => [
                            [
                                'label' => 'Manivelle',
                                'value' => 'default'
                            ],
                            [
                                'label' => 'Vaudreuil',
                                'value' => 'vaudreuil'
                            ]
                        ],
                    ],
                    [
                        'name' => 'hideHeader',
                        'type' => 'toggle',
                        'label' => trans('screen.inputs.hide_header')
                    ],
                    [
                        'name' => 'hideMenuSummary',
                        'type' => 'toggle',
                        'label' => trans('screen.inputs.hide_summaries')
                    ],
                    [
                        'name' => 'channelsMenuAlwaysVisible',
                        'type' => 'toggle',
                        'label' => trans('screen.inputs.channels_menu_always_visible')
                    ],
                    [
                        'name' => 'headerTitle',
                        'type' => 'text',
                        'label' => trans('screen.inputs.header_title')
                    ],
                    [
                        'name' => 'disableSlideshow',
                        'type' => 'toggle',
                        'label' => trans('screen.inputs.disable_slideshow')
                    ],
                    [
                        'name' => 'disableManivelle',
                        'type' => 'toggle',
                        'label' => trans('screen.inputs.disable_manivelle')
                    ],
                    [
                        'name' => 'defaultLocale',
                        'type' => 'select',
                        'label' => trans('screen.inputs.default_locale'),
                        'values' => $localesSelectValues,
                    ],
                    [
                        'name' => 'keyboardAlternativeLayout',
                        'type' => 'select',
                        'label' => trans('screen.inputs.keyboard_alternative_layout'),
                        'values' => [
                            [
                                'label' => 'QWERTY',
                                'value' => 'default'
                            ],
                            [
                                'label' => 'AZERTY',
                                'value' => 'azerty'
                            ],
                            [
                                'label' => 'UBO',
                                'value' => 'ubo'
                            ]
                        ],
                    ],
                ]
            ]
        ];
    }
}
