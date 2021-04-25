<?php

namespace Vukasinl\Modals;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Vukasinl\Modals\Skeleton\SkeletonClass
 */
class ModalsFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'modals';
    }
}
