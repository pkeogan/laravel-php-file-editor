<?php

namespace Pkeogan\Endpoints;

use Illuminate\Support\Str;
use Pkeogan\Support\AST\ASTQueryBuilder;
use Pkeogan\Endpoints\EndpointProvider;

class SyntacticSweetener extends EndpointProvider
{
    protected $words = [
        'a',
        'also',
        'and',
        'epic',
        'from',
        'have',
        'it',
        'make',
        'please',
        'should',
        'thanks',
        'then',
        'to',
    ];

    public function __call($method, $args)
    {
        // Do nothing :)
        return $this->file;
    }

    protected function getHandlerMethod($signature, $args)
    {
        return collect($this->words)->contains($signature) ? $signature : false;
    }

    public function getEndpoints()
    {
        return $this->words;
    }
}
