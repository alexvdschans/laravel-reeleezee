<?php

namespace AvdS\Reeleezee\Facades;

use Illuminate\Support\Facades\Facade;

class ReeleezeeFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'AvdS\Reeleezee\Reeleezee';
    }
}
