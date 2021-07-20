<?php namespace Manivelle\Channels\Announcements;

use Manivelle\Support\ChannelServiceProvider;

class AnnouncementsServiceProvider extends ChannelServiceProvider
{
    protected $channelTypes = [
        \Manivelle\Channels\Announcements\AnnouncementsChannel::class
    ];

    protected $bubbleTypes = [
        \Manivelle\Channels\Announcements\AnnouncementBubble::class
    ];
}
