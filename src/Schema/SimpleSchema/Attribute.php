<?php

namespace Pkeogan\Schema\SimpleSchema;

use Pkeogan\Schema\SimpleSchema\SimpleSchemaParser;

class Attribute
{
    public $name;
    public $directives;

    public function __construct($name, $directives)
    {
        $this->name = $name;
        $this->directives = $directives;
    }

    public function hasDirective(string $directiveName)
    {
        return $this->directives->where('name', $directiveName)->isNotEmpty();
    }
}
