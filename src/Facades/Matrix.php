<?php

namespace AdrianBrown\Matrix\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \AdrianBrown\Matrix\Matrix
 */
class Matrix extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'matrix';
    }
}
