<?php namespace Manivelle\Channels\Services;

use Manivelle\Support\BubbleType;

class ServiceBubble extends BubbleType
{
    protected $attributes = [
        'type' => 'service'
    ];

    public function attributes()
    {
        return [
            'label' => trans('bubbles/service.label')
        ];
    }

    public function fields()
    {
        return [
            [
                'name' => 'title',
                'type' => 'text',
                'label' => trans('bubbles/service.fields.title')
            ],
            [
                'name' => 'service',
                'type' => 'text',
                'label' => trans('bubbles/service.fields.service')
            ],
            [
                'name' => 'description',
                'type' => 'text',
                'label' => trans('bubbles/service.fields.description'),
                'attributes' => [
                    'type' => 'textarea'
                ]
            ],
            [
                'name' => 'link',
                'type' => 'text',
                'label' => trans('bubbles/service.fields.link')
            ],
            [
                'name' => 'credits',
                'type' => 'text',
                'label' => trans('bubbles/service.fields.credits')
            ],
            [
                'name' => 'picture',
                'type' => 'picture',
                'label' => trans('bubbles/service.fields.picture')
            ]
        ];
    }

    public function snippet()
    {
        $snippet = parent::snippet();

        return array_merge($snippet, [
            'subtitle' => function ($fields) {
                return $fields->title ? $fields->title:'';
            },
            'subtitle' => function ($fields) {
                return $fields->service ? $fields->service:'';
            },
            'picture' => function ($fields) {
                return $fields->picture ? $fields->picture:null;
            },
            'description' => function ($fields) {
                return $fields->description ? $fields->description:'';
            },
            'summary' => function ($fields) {
                return $fields->credits ? $fields->credits:'';
            },
            'link' => function ($fields) {
                return $fields->link;
            }
        ]);
    }

    public function filters()
    {
        return [

        ];
    }

    /**
     * Email
     */
    public function getEmailLayout()
    {
        return 'photo-small';
    }

    public function getEmailTopButton($url)
    {
        return [
            'label' => trans('share.bubbles.service.see_entry'),
            'url' => $url
        ];
    }
}
