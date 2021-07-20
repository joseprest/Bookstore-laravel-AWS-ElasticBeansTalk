<?php

namespace Manivelle\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \Manivelle\Console\Commands\Test::class, //À enlever

        \Manivelle\Console\Commands\DbInstall::class,
        \Manivelle\Console\Commands\DbReset::class,

        \Manivelle\Console\Commands\CleanBubbles::class,
        \Manivelle\Console\Commands\CleanPings::class,
        \Manivelle\Console\Commands\CleanPlaylists::class,
        \Manivelle\Console\Commands\CleanMediatheque::class,
        \Manivelle\Console\Commands\CleanSyncs::class,
        \Manivelle\Console\Commands\CleanScreenCaches::class,
        \Manivelle\Console\Commands\CleanMetadatas::class,
        \Manivelle\Console\Commands\CleanS3::class,

        \Manivelle\Console\Commands\CachesClear::class,
        \Manivelle\Console\Commands\CachesCreate::class,

        \Manivelle\Console\Commands\ImagesCreate::class,

        \Manivelle\Console\Commands\SourcesList::class,
        \Manivelle\Console\Commands\SourcesStart::class,
        \Manivelle\Console\Commands\SourcesStop::class,
        \Manivelle\Console\Commands\SourcesFinish::class,
        \Manivelle\Console\Commands\SourcesClear::class,
        \Manivelle\Console\Commands\SourcesStatus::class,
        \Manivelle\Console\Commands\SourcesJobsClear::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Sync sources
        $this->commandOnOneServer($schedule, 'manivelle:sources:start')
            ->twiceDaily(0, 11)
            ->appendOutputTo(config('app.log_scheduler'));

        $this->commandOnOneServer($schedule, 'manivelle:sources:finish all')
            ->dailyAt('23:00')
            ->days([0,2,4])
            ->appendOutputTo(config('app.log_scheduler'));

        // Refresh cache
        $this->commandOnOneServer($schedule, 'manivelle:caches:create --clear --type=screens')
            ->cron('0 */2 * * *')
            ->appendOutputTo(config('app.log_scheduler'));

        $this->commandOnOneServer($schedule, 'manivelle:caches:create --clear')
            ->dailyAt('02:00')
            ->appendOutputTo(config('app.log_scheduler'));

        // Cleanup
        if (config('app.worker')) {
            $this->commandOnOneServer($schedule, 'manivelle:clean:mediatheque')
                ->dailyAt('22:00')
                ->appendOutputTo(config('app.log_scheduler'));

            $this->commandOnOneServer($schedule, 'manivelle:clean:bubbles')
                ->dailyAt('00:00')
                ->appendOutputTo(config('app.log_scheduler'));

            $this->commandOnOneServer($schedule, 'manivelle:clean:syncs --force')
                ->dailyAt('23:00')
                ->appendOutputTo(config('app.log_scheduler'));

            $this->commandOnOneServer($schedule, 'manivelle:clean:playlists --force')
                ->dailyAt('01:00')
                ->appendOutputTo(config('app.log_scheduler'));

            $this->commandOnOneServer($schedule, 'manivelle:clean:pings --force')
                ->daily()
                ->appendOutputTo(config('app.log_scheduler'));
        }
    }

    protected function commandOnOneServer(Schedule $schedule, $command)
    {
        $now = Carbon::now();
        return $schedule->command($command)->when(function () use ($command, $now) {
            $cacheKey = 'schedule_' . sprintf('schedule_%s_%s', md5($command), $now->format('Hi'));
            return app('cache')->add($cacheKey, true, 60);
        });
    }
}
