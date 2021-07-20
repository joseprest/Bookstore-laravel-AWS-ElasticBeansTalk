<?php namespace Manivelle\Channels\Publications;

use Manivelle\Support\ChannelServiceProvider;

class PublicationsServiceProvider extends ChannelServiceProvider
{
    protected $channelTypes = [
        \Manivelle\Channels\Publications\PublicationsChannel::class
    ];

    protected $bubbleTypes = [
        \Manivelle\Channels\Publications\PublicationBubble::class
    ];

    protected $fields = [];
}
