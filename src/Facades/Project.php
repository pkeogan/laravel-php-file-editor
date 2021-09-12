<?php

namespace Pkeogan\Facades;

use Illuminate\Support\Facades\Facade;

class Project extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Pkeogan\Facades\Project';
    }
}
