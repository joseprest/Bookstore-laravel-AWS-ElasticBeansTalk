<?php namespace Manivelle\Channels\Announcements;

use Manivelle\Support\BubbleType;
use Carbon\Carbon;

class AnnouncementBubble extends BubbleType
{
    protected $attributes = [
        'type' => 'announcement',
    ];

    public function attributes()
    {
        return [
            'label' => trans('bubbles/announcement.label'),
        ];
    }

    public function fields()
    {
        return [
            [
                'name' => 'title',
                'type' => 'text',
                'label' => trans('bubbles/announcement.fields.title'),
            ],
            [
                'name' => 'description',
                'type' => 'text',
                'label' => trans('bubbles/announcement.fields.description'),
                'attributes' => [
                    'type' => 'textarea',
                ],
            ],
            [
                'name' => 'link',
                'type' => 'text',
                'label' => trans('bubbles/announcement.fields.link'),
            ],
            [
                'name' => 'picture',
                'type' => 'picture',
                'label' => trans('bubbles/announcement.fields.picture'),
            ],
            [
                'name' => 'published_at',
                'type' => 'date',
                'label' => trans('bubbles/announcement.fields.published_at'),
            ],
        ];
    }

    public function snippet()
    {
        $snippet = parent::snippet();

        return array_merge($snippet, [
            'title' => function ($fields) {
                return $fields->title ? $fields->title : '';
            },
            'subtitle' => function ($fields) {
                if (!isset($fields->published_at) || empty($fields->published_at)) {
                    return null;
                }
                $date = Carbon::parse($fields->published_at);
                return $date->formatLocalized('%A %d %B');
            },
            'picture' => function ($fields) {
                return $fields->picture ? $fields->picture : null;
            },
            'description' => function ($fields) {
                if (isset($fields->description)) {
                    $description = strip_tags(
                        html_entity_decode($fields->description, ENT_QUOTES, 'utf-8')
                    );
                    return mb_strlen($description) > 600
                        ? substr($description, 0, strpos($description, ' ', 500)) . '...'
                        : $description;
                }
                return '';
            },
            'summary' => function ($fields) {
                return $fields->description ? $fields->description : '';
            },
            'link' => function ($fields) {
                return $fields->link;
            },
        ]);
    }

    public function filters()
    {
        return [];
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
            'label' => trans('share.bubbles.announcement.see_entry'),
            'url' => $url,
        ];
    }
}
