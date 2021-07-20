<?php

namespace Manivelle\Events;

use Manivelle\Models\Playlist;
use Illuminate\Queue\SerializesModels;

class PlaylistChanged extends Event
{
    use SerializesModels;
    
    public $playlist;
    public $timelineOnly;
    
    public function __construct(Playlist $playlist, $timelineOnly = false)
    {
        $this->playlist = $playlist;
        $this->timelineOnly = $timelineOnly;
    }
}
