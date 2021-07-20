<?php namespace Manivelle\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Manivelle extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'manivelle';
    }
}
