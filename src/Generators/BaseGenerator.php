<?php

namespace Pkeogan\Generators;

use Pkeogan\Schema\SimpleSchema\SimpleSchema;

abstract class BaseGenerator
{
    public SimpleSchema $schema;

    public function __construct(SimpleSchema $schema)
    {
        $this->schema = $schema;
    }

    public static function make(SimpleSchema $schema)
    {
        return new static($schema);
    }

    abstract public function qualifies();

    abstract public function build();
}
