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

use Twilio\Rest\Client as TwilioClient;

class ShareBubbleSMSJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, ShareBubbleUrl;

    public $connection = 'priority';

    public $bubble;
    public $organisation;
    public $phone;
    public $countryCode;
    public $locale;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Bubble $bubble, $phone, $countryCode = '+1', Organisation $organisation = null, $locale)
    {
        $this->bubble = $bubble;
        $this->organisation = $organisation;
        $this->phone = $phone;
        $this->countryCode = preg_replace('/^\+?(.*)$/', '+$1', !empty($countryCode) ? $countryCode : '+1');
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

        $to = $this->phone;
        $from = config('services.twilio.from.'.$this->countryCode, config('services.twilio.from.+1'));
        $body = trans('share.sms.body', [
            'url' => $url
        ]);
        if ($this->organisation &&
            isset($this->organisation->settings->sms_body) &&
            !empty($this->organisation->settings->sms_body)
        ) {
            $body = trim($this->organisation->settings->sms_body).' '.$url;
        }

        $client = new TwilioClient(config('services.twilio.sid'), config('services.twilio.token'));
        $message = $client->messages->create($to, array(
            'from' => $from,
            'body' => $body
        ));

        $log->info('Share SMS sent to '.$this->phone.' for bubble '.$this->bubble->id);
    }

    public function getUrl()
    {
        $url = $this->bubble->snippet->link;
        if ($this->organisation && preg_replace('/(https?\:\/\/)[^\.]+(\.pretnumerique)/', $url)) {
            $slug = data_get($this->organisation->settings, 'pretnumerique_id', $this->organisation->slug);
            $url = preg_replace('/(https?\:\/\/)[^\.]+(\.pretnumerique)/', '$1'.$slug.'$2', $url);
        }

        return $url;
    }
}
