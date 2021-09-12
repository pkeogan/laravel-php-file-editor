<?php

namespace Pkeogan\Endpoints\PHP;

use Pkeogan\Endpoints\EndpointProvider;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\BuilderFactory;
use PhpParser\NodeFinder;
use PhpParser\Node\Stmt\Use_ as PhpParserUse_;
use PhpParser\NodeTraverser;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Use_ extends EndpointProvider
{
    /**
     * @example Get file uses
     * @source $file->use()
     *
     * @example Set file uses
     * @source $file->use(['ClassA', 'classB'])
     *
     * @example Use with alias
     * @source $file->use('ClassA as Ajthinking')
     *
     * @example Add file uses
     * @source $file->add()->use('AdditionalClass')
     *
     * @return mixed
     */
    public function use($value = null)
    {
        if ($this->file->directive('add')) {
            return $this->add($value);
        }

        if ($value === null) {
            return $this->get();
        }
        
        return $this->set($value);
    }

    protected function get()
    {
        return $this->file->astQuery()
            ->use()
            ->uses
            ->get()
            ->map(function ($useStatement) {
                $base = join('\\', $useStatement->name->parts);
                return $base . ($useStatement->alias ? ' as ' . $useStatement->alias : '');
            });
    }

    protected function set($newUseStatements)
    {
        $this->file->astQuery()
            ->use()
            ->remove()
            ->commit();

        return $this->add($newUseStatements);
    }

    protected function add($newUseStatements)
    {
        $currentUseStatements = collect($this->get());

        if(!$currentUseStatements->has('$newUseStatements'))
        {
            $newUseStatements = str_replace('/', '\\', $newUseStatements);
            $currentUseStatements->push($newUseStatements);
        }

         $currentUseStatements = $currentUseStatements->sortByDesc(function($string) {
            return strlen($string);
        })->toArray();

        $this->file->astQuery()
            ->use()
            ->remove()
            ->commit();
            


        collect(Arr::wrap(collect($currentUseStatements)->unique()->toArray()))->each(function ($name) {

            $this->file->astQuery()
            ->insertStmt(
                $this->useStatement($name)
            )
            ->commit();

        });

        return $this->file->continue();
    }

    protected function useStatement($signature)
    {
        $parts = Str::of($signature)->explode(' as ');
        $name = $parts->first();
        $statement = (new BuilderFactory)->use($name);
        
        if ($parts->last() != $parts->first()) {
            $statement = $statement->as($parts->last());
        }

        return $statement->getNode();
    }
}
