<?php namespace Manivelle\Providers;

use Illuminate\Support\ServiceProvider;
use Storage;
use Cache;

class ImageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $image = $this->app['image'];
        
        $image->filter('background_blur', function ($image) {
            $mode = \Imagine\Image\ImageInterface::THUMBNAIL_INSET;
            $size = new \Imagine\Image\Box(1000, 1000);
            $thumbnail = $image->thumbnail($size, $mode);
            $thumbnail->getImagick()->blurImage(35, 20);
            return $thumbnail;
        });
        
        $image->filter('avatar_small', [
            'width' => 100,
            'height' => 100,
            'crop' => true
        ]);
        
        $image->filter('thumbnail', [
            'width' => 300,
            'height' => 300
        ]);
        
        $image->filter('thumbnail_snippet', [
            'width' => 150,
            'height' => 150
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
