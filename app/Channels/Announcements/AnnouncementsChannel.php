<?php namespace Manivelle\Channels\Announcements;

use Manivelle\Support\ChannelType;

use Manivelle\Panneau\Fields\AuthorField;
use Manivelle\Models\Bubble;
use Panneau\Fields\Date as DateField;

class AnnouncementsChannel extends ChannelType
{

    protected $attributes = [
        'type' => 'announcements',
        'bubbleType' => 'announcement'
    ];

    public function settings()
    {
        return [];
    }

    public function filters()
    {

        return [

        ];
    }
}
