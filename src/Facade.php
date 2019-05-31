<?php

namespace Tracing;

/**
 * Class Facade
 *
 * @package Tracing
 */
class Facade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'tracing';
    }
}
