<?php namespace Manivelle\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Localizer extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'localizer';
    }
}
