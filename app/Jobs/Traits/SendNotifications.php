<?php

namespace Manivelle\Jobs\Traits;

trait SendNotifications
{
    protected function getNotificationChannelName($name)
    {
        $namespace = config('services.pubnub.namespace');
        $parts = [];
        if (!empty($namespace)) {
            $parts[] = $namespace;
        }
        $parts[] = $name;
        
        return implode(':', $parts);
    }
}
