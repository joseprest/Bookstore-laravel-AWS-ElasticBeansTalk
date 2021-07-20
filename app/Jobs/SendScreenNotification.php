<?php

namespace Manivelle\Jobs;

use Manivelle\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Manivelle\Jobs\Traits\SendNotifications;

use Manivelle\Models\Screen;
use Pubnub\Pubnub;
use Illuminate\Log\Writer;
use Symfony\Component\Console\Output\ConsoleOutput;
use Log;

class SendScreenNotification extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, SendNotifications;

    public $connection = 'priority';

    public $screen;
    public $action;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Screen $screen, $action = 'screen:update')
    {
        $this->screen = $screen;
        $this->action = $action;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Pubnub $pubnub, Writer $log, ConsoleOutput $output)
    {
        $model = $this->screen;
        $channel = $this->getNotificationChannelName('screen_'.$model->id);
        $message = [
            'action' => $this->action
        ];
        if (substr($this->action, 0, 7) === 'screen:') {
            $message['payload'] = $model->toArray();
        }

        $result = $pubnub->publish($channel, $message);

        if (app()->runningInConsole()) {
            $output->writeLn('<info>Screen notification:</info> '.$channel.' - '.json_encode($message));
        } else {
            $log->info('Screen notification: '.$channel, $message);
        }
    }
}
