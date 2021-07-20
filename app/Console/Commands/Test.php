<?php namespace Manivelle\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class Test extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test de mathieu';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $bubble = factory(\Manivelle\Models\Bubble::class)->make();
        $channel = factory(\Manivelle\Models\Channel::class)->make();
        $screen = factory(\Manivelle\Models\Screen::class)->make();

        dd($screen->toArray());

    }
}
