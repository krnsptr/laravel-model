<?php

namespace Krnsptr\LaravelModel;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Krnsptr\LaravelModel\Skeleton\SkeletonClass
 */
class LaravelModelFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-model';
    }
}
