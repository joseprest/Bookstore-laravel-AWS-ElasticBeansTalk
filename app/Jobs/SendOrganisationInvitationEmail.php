<?php

namespace Manivelle\Jobs;

use Manivelle\Jobs\Job;
use Manivelle\Models\OrganisationInvitation;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Log\Writer;
use Illuminate\Contracts\Mail\Mailer;

class SendOrganisationInvitationEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    
    public $connection = 'priority';
    
    public $invitation;
    public $locale;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(OrganisationInvitation $invitation, $locale)
    {
        $this->invitation = $invitation;
        $this->locale = $locale;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Writer $log, Mailer $mailer)
    {
        $invitation = $this->invitation;
        $email = $this->invitation->email;
        $user = $this->invitation->user;
        $role = $this->invitation->role;
        $organisation = $this->invitation->organisation;
        
        $mailer->send('emails.invitation', [
            'invitation' => $invitation,
            'role' => $role,
            'organisation' => $organisation,
            'locale' => $this->locale
        ], function ($mail) use ($user, $email, $organisation) {
            $mail->subject(trans('invitation.email.subject', ['organisation' => $organisation->name]));
            
            if ($user) {
                $mail->to($user->email, $user->name);
            } else {
                $mail->to($email);
            }
        });
        
        $log->info('Invitation Email sent to '.($user ? $user->email:$email));
    }
}
