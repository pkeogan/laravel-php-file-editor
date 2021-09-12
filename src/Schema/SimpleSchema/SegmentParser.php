<?php

namespace Pkeogan\Schema\SimpleSchema;

use Pkeogan\Schema\SimpleSchema\Entities\ModelEntity;
use Pkeogan\Schema\SimpleSchema\Entities\UserEntity;
use Pkeogan\Schema\SimpleSchema\SimpleSchema;
use Str;

class SegmentParser
{
    public static function make()
    {
        return new static();
    }

    public function parse($segment)
    {
        $segmentRows = Str::of($segment)->split('/' . PHP_EOL . '/');
        
        $headline = $segmentRows->first();
        
        

        $headlineParts = Str::of($headline)->split('/ /');

        $name = $headlineParts->first();

        

        $directives = $headlineParts->slice(1)->toArray();

        $attributes = $segmentRows->slice(1)->values()->map(function ($row) {
            $attributeParts = Str::of($row)->split('/ /');
            $directives = $attributeParts->slice(1)->values()->map(function ($directiveString) {
                $directiveName = (string) Str::of($directiveString)->before(':');
                
                if (Str::of($directiveString)->contains(':')) {
                    $directiveArgs = Str::of($directiveString)->after(':')->split('/\,/');
                } else {
                    $directiveArgs = collect();
                }

                return new Directive($directiveName, $directiveArgs);
            });
            $name = $attributeParts->first();
            return new Attribute($name, $directives);
        });

        if ($name == 'User') {
            return new UserEntity($name, $directives, $attributes);
        }

        return new ModelEntity($name, $directives, $attributes);
    }
}
