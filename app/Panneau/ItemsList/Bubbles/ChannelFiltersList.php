<?php namespace Manivelle\Panneau\ItemsList\Bubbles;

use Panneau\Support\ItemsList;
use Log;

class ChannelFiltersList extends ItemsList
{

    protected $attributes = array(
        'name' => 'bubbles.channel.filters',
        'type' => 'bubbles_channel_filters'
    );
    
    public function render()
    {
        try {
            return parent::render();
        } catch (\Exception $e) {
            Log::error($e);
            throw $e;
        }
    }
}
