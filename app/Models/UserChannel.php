<?php namespace Manivelle\Models;

use Illuminate\Database\Eloquent\Model;

class UserChannel extends ChannelUser
{
    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        $value = $this->getAttribute($key);
        
        if ($value === null && $this->channel) {
            return $this->channel->getAttribute($key);
        }
        
        return $value;
    }
}
