<?php namespace Manivelle\Channels\Services;

use Manivelle\Support\ChannelType;

use Manivelle\Panneau\Fields\AuthorField;
use Manivelle\Models\Bubble;
use Panneau\Fields\Date as DateField;

class ServicesChannel extends ChannelType
{

    protected $attributes = [
        'type' => 'services',
        'bubbleType' => 'service'
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
