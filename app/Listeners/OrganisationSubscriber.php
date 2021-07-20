<?php

namespace Manivelle\Listeners;

use Log;
use Event;
use App;

use Manivelle\Events\Event as BaseEvent;
use Manivelle\Events\OrganisationUserInvited;
use Manivelle\Jobs\SendOrganisationInvitationEmail;

class OrganisationSubscriber
{
    
    /**
     * Listener when a user is invited to an Organisation
     * @param  Manivelle\Events\OrganisationUserInvited $event The organisation user invited event
     * @return void
     */
    public function onOrganisationUserInvited(OrganisationUserInvited $event)
    {
        $model = $event->invitation;
        
        $locale = App::getLocale();
        
        dispatch(new SendOrganisationInvitationEmail($model, $locale));
    }
    
    public function subscribe($events)
    {
        $events->listen(
            OrganisationUserInvited::class,
            '\Manivelle\Listeners\OrganisationSubscriber@onOrganisationUserInvited'
        );
    }
}
