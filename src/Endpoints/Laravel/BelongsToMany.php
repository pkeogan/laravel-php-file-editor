<?php

namespace Pkeogan\Endpoints\Laravel;

use Pkeogan\Endpoints\EndpointProvider;
use Pkeogan\Support\Snippet;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class BelongsToMany extends EndpointProvider
{
    /**
     * @example Add a belongsToMany relationship method
     * @source $file->belongsToMany('Company')
     */
    public function belongsToMany($targets)
    {
        return $this->add($targets);
    }

    protected function add($targets)
    {
        return $this->file->astQuery()
            ->class()
            ->insertStmts(
                collect(Arr::wrap($targets))->map(function ($target) {
                    return Snippet::___BELONGS_TO_MANY_METHOD___([
                        '___BELONGS_TO_MANY_METHOD___' => Str::belongsToManyMethodName($target),
                        '___TARGET_CLASS___' => class_basename($target),
                        '___TARGET_IN_DOC_BLOCK___' => Str::belongsToManyDocBlockName($target)
                    ]);
                })->toArray()
            )->commit()
            ->end()
            ->continue();
    }
}
