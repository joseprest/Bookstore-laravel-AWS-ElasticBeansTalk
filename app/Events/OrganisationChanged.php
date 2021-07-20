<?php

namespace Manivelle\Events;

use Manivelle\Models\Organisation;
use Illuminate\Queue\SerializesModels;

class OrganisationChanged extends Event
{
    use SerializesModels;
    
    public $organisation;
    
    public function __construct(Organisation $organisation)
    {
        $this->organisation = $organisation;
    }
}
