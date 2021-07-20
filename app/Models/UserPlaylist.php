<?php namespace Manivelle\Models;

use Illuminate\Database\Eloquent\Model;

class UserPlaylist extends PlaylistUser
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
        
        if ($value === null && $this->playlist) {
            return $this->playlist->getAttribute($key);
        }
        
        return $value;
    }
}
