<?php

namespace Pkeogan\Schema\SimpleSchema;

use Pkeogan\Schema\SimpleSchema\SimpleSchemaParser;

class SimpleSchema
{
    public $entities;

    public function __construct(\Illuminate\Support\Collection $entities)
    {
        $this->entities = $entities;
    }

    public static function parse(string $schema)
    {
        return (new SimpleSchemaParser)->parse($schema);
    }
}
