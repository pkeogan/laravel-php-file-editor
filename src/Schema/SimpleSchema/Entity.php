<?php

namespace Pkeogan\Schema\SimpleSchema;

use Pkeogan\Schema\SimpleSchema\SimpleSchemaParser;

abstract class Entity
{
    public $name;
    public $directives;
    public $attributes;

    public function __construct($name, $directives, $attributes)
    {
        $this->name = $name;
        $this->directives = $directives;
        $this->attributes = $attributes;
    }
}
