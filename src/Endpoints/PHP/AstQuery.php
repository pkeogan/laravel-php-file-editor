<?php

namespace Pkeogan\Endpoints\PHP;

use Illuminate\Support\Str;
use Pkeogan\Support\AST\ASTQueryBuilder;
use Pkeogan\Endpoints\EndpointProvider;

class AstQuery extends EndpointProvider
{
    /**
     * @example Get a AstQueryBuilder instance
     * @source $file->astQuery()
     *
     * @return Pkeogan\Support\AST\ASTQueryBuilder
     */
    public function astQuery()
    {
        // Create AST builder instance
        $builder = new ASTQueryBuilder($this->file->ast());
        
        // Attach the file so we can return it later
        $builder->file = $this->file;
        
        return $builder;
    }
}
