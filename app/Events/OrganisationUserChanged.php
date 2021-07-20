<?php

namespace Manivelle\Events;

use Manivelle\Models\OrganisationUser;
use Illuminate\Queue\SerializesModels;

class OrganisationUserChanged extends Event
{
    use SerializesModels;
    
    public $user;
    
    public function __construct(OrganisationUser $user)
    {
        $this->user = $user;
    }
}
