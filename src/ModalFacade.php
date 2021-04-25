<?php

namespace Vukasinl\LaravelBladeModals;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Vukasinl\LaravelBladeModals\Skeleton\SkeletonClass
 */
class ModalFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'modal';
    }
}
