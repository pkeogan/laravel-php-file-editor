<?php

namespace Pkeogan\Schema\SimpleSchema;

use Pkeogan\Schema\SimpleSchema\SimpleSchemaParser;

class Directive
{
    public $name;
    public $arguments;

    public function __construct($name, $arguments)
    {
        $this->name = $name;
        $this->arguments = $arguments;
    }
}
