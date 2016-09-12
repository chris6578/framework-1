<?php

namespace LaraPress\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Loop extends Facade {

    protected static function getFacadeAccessor()
    {
        return 'loop';
    }
}
