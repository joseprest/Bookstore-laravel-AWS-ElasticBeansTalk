<?php

namespace Manivelle\Jobs;

use Manivelle\Jobs\Job;
use Manivelle\Jobs\Traits\ShareBubbleUrl;
use Manivelle\Models\Bubble;
use Manivelle\Models\Organisation;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Log\Writer;

use Mail;

class ShareBubbleEmailJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, ShareBubbleUrl;
    
    public $connection = 'priority';
    
    public $bubble;
    public $organisation;
    public $email;
    public $locale;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Bubble $bubble, array $email, Organisation $organisation = null, $locale)
    {
        $this->bubble = $bubble;
        $this->organisation = $organisation;
        $this->email = $email;
        $this->locale = $locale;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Writer $log)
    {
        $url = $this->getShareBubbleUrl($this->bubble, $this->organisation);
        
        $to = array_get($this->email, 'to');
        $from = array_get($this->email, 'from', config('mail.from'));
        $message = array_get($this->email, 'message', null);
        $subject = array_get($this->email, 'subject', trans('share.email.subject'));
        
        Mail::send('emails.share_message', [
            'email' => $to,
            'userMessage' => $message,
            'bubble' => $this->bubble,
            'locale' => $this->locale,
            'organisation' => $this->organisation,
            'url' => $url
        ], function ($mail) use ($to, $from, $subject) {
            $mail->from($from['address'], $from['name']);
            $mail->to($to);
            $mail->subject($subject);
        });
        
        $log->info('Share Email sent to '.$to.' for bubble '.$this->bubble->id);
    }
}
