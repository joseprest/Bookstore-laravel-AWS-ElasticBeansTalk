<?php namespace Manivelle\Panneau\ItemsList\Bubbles;

use Panneau;
use Panneau\Support\ItemsList;

class PlaylistList extends ItemsList
{
    
    protected $attributes = array(
        'name' => 'bubbles.playlist',
        'type' => 'bubbles_playlist',
        'layout' => 'playlist'
    );
    
    protected $playlist;
    
    public function withPlaylist($playlist)
    {
        $this->playlist = $playlist;
        
        return $this;
    }
    
    public function render()
    {
        $contents = parent::render();
        return $contents;
    }
}
