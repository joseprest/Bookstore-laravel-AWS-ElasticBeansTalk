<?php namespace Manivelle\View\Composers\Layouts;

use Asset;
use Panneau;

class ScreenComposer
{

    public function compose($view)
    {
        Asset::container('header')->style('screen', 'css/screen.css');
        Asset::container('header')->script('modernizr', 'js/vendor/modernizr.js');
        Asset::container('header')->script('fmc_adobe', '//assets.adobedtm.com/41b7a8e674452e42c4a9f83d28f8193e334610be/satelliteLib-7d92ac1c6840397bad9d6186e49b1298bbf5fe24.js');


        Asset::container('footer')->script('screen_vendor', 'js/vendor/screen.js');
        Asset::container('footer')->script('screen', 'js/screen.js', ['manivelle', 'screen_vendor']);
    }
}
