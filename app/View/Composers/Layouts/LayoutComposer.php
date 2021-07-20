<?php namespace Manivelle\View\Composers\Layouts;

use Asset;
use Panneau;

class LayoutComposer
{

    public function compose($view)
    {
        Asset::container('header')->style('main', 'css/main.css');

        Asset::container('footer')->script('main', 'js/main.js');
    }
}
