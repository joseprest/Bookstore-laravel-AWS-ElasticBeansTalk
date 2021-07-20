<?php namespace Manivelle\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class DbInstall extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'db:install {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install database';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->call('migrate', array(
            '--force' => $this->option('force')
        ));
        $this->call('db:seed', array(
            '--force' => $this->option('force')
        ));
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('force', 'f', InputOption::VALUE_NONE, 'Force the operation to run when in production.', null)
        );
    }
}
