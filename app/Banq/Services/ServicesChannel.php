<?php namespace Manivelle\Banq\Services;

use Manivelle\Support\ChannelType;

use Manivelle\Panneau\Fields\AuthorField;
use Manivelle\Models\Bubble;
use Panneau\Fields\Date as DateField;

class ServicesChannel extends ChannelType
{

    protected $attributes = [
        'type' => 'banq_services',
        'bubbleType' => 'banq_service'
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
