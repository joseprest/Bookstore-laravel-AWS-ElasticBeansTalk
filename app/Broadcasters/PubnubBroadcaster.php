<?php

namespace Manivelle\Broadcasters;

use Pubnub\Pubnub;
use Illuminate\Contracts\Broadcasting\Broadcaster;

class PubnubBroadcaster implements Broadcaster
{
    /**
     * The Pubnub SDK instance.
     *
     * @var \Pubnub\Pubnub
     */
    protected $pubnub;
    
    /**
     * The channel namespace
     *
     * @var string
     */
    protected $namespace;

    /**
     * Create a new broadcaster instance.
     *
     * @param  \Pusher  $pusher
     * @return void
     */
    public function __construct(Pubnub $pubnub, $namespace = null)
    {
        $this->pubnub = $pubnub;
        $this->namespace = $namespace;
    }

    /**
     * {@inheritdoc}
     */
    public function broadcast(array $channels, $event, array $payload = [])
    {
        $payload = [
            'event' => $event,
            'data' => $payload
        ];
        
        foreach ($channels as $channel) {
            $channel = $this->getChannelWithNamespace($channel);
            $this->pubnub->publish($channel, $payload);
        }
    }

    /**
     * Get the channel with namespace
     *
     * @param string $channel The name of the channel
     * @return string
     */
    protected function getChannelWithNamespace($channel)
    {
        $parts = [];
        if (!empty($this->namespace)) {
            $parts[] = $this->namespace;
        }
        $parts[] = $channel;
        
        return implode(':', $parts);
    }

    /**
     * Get the Pubnub SDK instance.
     *
     * @return \Pubnub\Pubnub
     */
    public function getPubnub()
    {
        return $this->pubnub;
    }

    /**
     * Get the channel namespace
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }
}
