<?php namespace Manivelle\Channels\Locations;

use Manivelle\Support\ChannelServiceProvider;

class LocationsServiceProvider extends ChannelServiceProvider
{
    protected $channelTypes = [
        \Manivelle\Channels\Locations\LocationsChannel::class
    ];

    protected $bubbleTypes = [
        \Manivelle\Channels\Locations\LocationBubble::class
    ];
}
