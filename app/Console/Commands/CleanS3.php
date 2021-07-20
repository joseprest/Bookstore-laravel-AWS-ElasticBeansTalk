<?php

namespace Manivelle\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

use Log;
use Artisan;
use DB;
use Storage;

class CleanS3 extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manivelle:clean:s3 {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean s3';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $force = $this->option('force');
        $disk = Storage::disk('s3');
        $datesDirectories = $disk->directories('image');
        foreach ($datesDirectories as $dateDir) {
            $allFiles = $disk->files($dateDir);
            $images = [];
            foreach ($allFiles as $file) {
                if (preg_match('/([0-9]+\-[0-9]{6})\.([a-z]{3,4})$/', $file, $matches)) {
                    $images[$file] = [];
                    foreach ($allFiles as $subFile) {
                        if (preg_match('/image\/[0-9-]+\/'.preg_quote($matches[1], '/').'/', $subFile)) {
                            $images[$file][] = $subFile;
                        }
                    }
                }
            }

            $this->line('<info>Found:</info> '.sizeof(array_keys($images)).' images in '.$dateDir.'.');

            $filesToDelete = [];
            foreach ($images as $key => $files) {
                $item = DB::table('mediatheque_pictures')->where('filename', $key)->first();
                if (!$item) {
                    $filesToDelete = array_merge($filesToDelete, $files);
                }
            }

            if ($force || $this->confirm('Are you sure you want to delete '.sizeof($filesToDelete).' images?')) {
                $disk->delete($filesToDelete);
                $this->line('<info>Deleted:</info> '.sizeof($filesToDelete).' files.');
            }

            $noMoreFiles = sizeof($filesToDelete) === sizeof($allFiles) || sizeof($allFiles) == 0;
            if ($noMoreFiles && ($force || $this->confirm('Are you sure you want to delete the folder '.$dateDir.'?'))) {
                $disk->deleteDirectory($dateDir);
            }
        }
    }
}
