<?php

namespace Manivelle\Events;

use Manivelle\Models\OrganisationInvitation;
use Illuminate\Queue\SerializesModels;

class OrganisationUserInvited extends Event
{
    use SerializesModels;
    
    public $invitation;
    
    public function __construct(OrganisationInvitation $invitation)
    {
        $this->invitation = $invitation;
    }
}
