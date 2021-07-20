<?php

namespace Manivelle\Listeners;

use Log;
use Event;
use Manivelle\Events\Event as BaseEvent;

use Manivelle\Events\ChannelTypeRegistered;
use Manivelle\Events\BubbleTypeRegistered;

class ManivelleSubscriber
{
    /**
     * Listener when a ChannelType is registered
     * @param  Manivelle\Events\ChannelTypeRegistered $event The ChannelType registered event
     * @return void
     */
    public function onChannelTypeRegistered(ChannelTypeRegistered $event)
    {
        $key = $event->key;
        $channelType = $event->channelType;
        $graphql = app('graphql');
        
        $channelFieldsTypeName = 'Channel'.studly_case($key).'Fields';
        $graphQLType = \Manivelle\GraphQL\Type\ChannelFieldsType::class;
        $type = app($graphQLType);
        $type->setChannelType($channelType);
        $graphql->addType($type, $channelFieldsTypeName);
    }
    
    /**
     * Listener when a BubbleType is registered
     * @param  Manivelle\Events\BubbleTypeRegistered $event The BubbleType registered event
     * @return void
     */
    public function onBubbleTypeRegistered(BubbleTypeRegistered $event)
    {
        $key = $event->key;
        $bubbleType = $event->bubbleType;
        $graphql = app('graphql');
        
        //Add fields type to graphql
        $bubbleFieldsTypeName = 'Bubble'.studly_case($key).'Fields';
        $graphQLType = \Manivelle\GraphQL\Type\BubbleFieldsType::class;
        $type = app($graphQLType);
        $type->setBubbleType($bubbleType);
        $graphql->addType($type, $bubbleFieldsTypeName);
        
        //Add filters type to graphql
        $bubbleFiltersTypeName = 'Bubble'.studly_case($key).'Filters';
        $graphQLType = \Manivelle\GraphQL\Type\BubbleFiltersType::class;
        $type = app($graphQLType);
        $type->setBubbleType($bubbleType);
        $graphql->addType($type, $bubbleFiltersTypeName);
    }
    
    public function subscribe($events)
    {
        $events->listen(
            ChannelTypeRegistered::class,
            '\Manivelle\Listeners\ManivelleSubscriber@onChannelTypeRegistered'
        );
        $events->listen(
            BubbleTypeRegistered::class,
            '\Manivelle\Listeners\ManivelleSubscriber@onBubbleTypeRegistered'
        );
    }
}
