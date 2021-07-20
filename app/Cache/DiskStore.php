<?php

namespace Manivelle\Cache;

use Illuminate\Cache\FileStore;
use Illuminate\Filesystem\FilesystemAdapter;

class DiskStore extends FileStore
{
    /**
     * Create a new file cache store instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @param  string  $directory
     * @return void
     */
    public function __construct(FilesystemAdapter $files, $directory)
    {
        $this->files = $files;
        $this->directory = $directory;
    }
}
