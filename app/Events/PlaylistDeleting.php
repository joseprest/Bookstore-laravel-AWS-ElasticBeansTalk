<?php

namespace Manivelle\Events;

use Manivelle\Models\Playlist;
use Illuminate\Queue\SerializesModels;

class PlaylistDeleting extends Event
{
    use SerializesModels;
    
    public $playlist;
    
    public function __construct(Playlist $playlist)
    {
        $this->playlist = $playlist;
    }
}
