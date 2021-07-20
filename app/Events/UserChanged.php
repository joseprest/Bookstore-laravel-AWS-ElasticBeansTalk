<?php

namespace Manivelle\Events;

use Manivelle\Models\OrganisationInvitation;
use Illuminate\Queue\SerializesModels;
use Manivelle\User;

class UserChanged extends Event
{
    use SerializesModels;
    
    public $user;
    
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
