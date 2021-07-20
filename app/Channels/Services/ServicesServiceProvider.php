<?php namespace Manivelle\Channels\Services;

use Manivelle\Support\ChannelServiceProvider;

class ServicesServiceProvider extends ChannelServiceProvider
{
    protected $channelTypes = [
        \Manivelle\Channels\Services\ServicesChannel::class
    ];

    protected $bubbleTypes = [
        \Manivelle\Channels\Services\ServiceBubble::class
    ];
}
