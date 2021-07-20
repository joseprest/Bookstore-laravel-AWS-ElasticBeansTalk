<?php

namespace Manivelle\Console\Commands;

use Illuminate\Console\Command;

use Manivelle\Models\Source;

class SourcesList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manivelle:sources:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List sources';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sources = Source::all();
        foreach ($sources as $source) {
            $this->line('<info>Source:</info> '.$source->name);
        }
    }
}
