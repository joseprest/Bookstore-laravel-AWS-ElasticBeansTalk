<?php namespace Manivelle\Panneau\ItemsList;

use Panneau;
use Panneau\Support\ItemsList;

class PlaylistList extends ItemsList
{
    
    protected $attributes = array(
        'name' => 'playlist',
        'type' => 'playlist',
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
