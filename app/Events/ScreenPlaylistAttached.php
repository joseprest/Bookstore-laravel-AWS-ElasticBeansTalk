<?php

namespace Manivelle\Events;

use Illuminate\Queue\SerializesModels;
use Manivelle\Models\Screen;
use Manivelle\Models\Playlist;

class ScreenPlaylistAttached extends Event
{
    use SerializesModels;
    
    public $screen;
    public $playlist;
    
    public function __construct(Screen $screen, Playlist $playlist)
    {
        $this->screen = $screen;
        $this->playlist = $playlist;
    }
}
