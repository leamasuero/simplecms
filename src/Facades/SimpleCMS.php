<?php

namespace Lebenlabs\SimpleCMS\Facades;

use Illuminate\Support\Facades\Facade;

class SimpleCMS extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'simplecms';
    }
}
