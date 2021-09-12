<?php

namespace Pkeogan\Commands\Demo;

use Pkeogan\LaravelFile;

class CustomizedLaravelFile extends LaravelFile
{
    public function itHasExtras()
    {
        return "Something extra!";
    }
}
