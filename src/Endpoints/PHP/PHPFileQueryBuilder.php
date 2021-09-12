<?php

namespace Pkeogan\Endpoints\PHP;

use Illuminate\Support\Str;
use Pkeogan\Endpoints\EndpointProvider;
use Pkeogan\Support\PSR2PrettyPrinter;
use Pkeogan\Support\RecursiveFileSearch;
use PhpParser\ParserFactory;
use Illuminate\Support\Facades\Storage;
use Error;
use UnexpectedValueException;
use Pkeogan\Traits\HasOperators;
use ReflectionClass;
use ReflectionMethod;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveCallbackFilterIterator;
use InvalidArgumentException;
use LaravelFile;

class PHPFileQueryBuilder extends EndpointProvider
{
    use HasOperators;

    const PHPSignature = '/\.php$/';
    
    public function __construct($file = null)
    {
        parent::__construct($file);
        $this->result = collect();
    }

    /**
     * @example Get a QueryBuilder instance
     * @source PHPFile::query()
     */
    public function query()
    {
        return $this;
    }
    
    /**
     * @example Get all files in root recursively
     * @source PHPFile::all()
     */
    public function all()
    {
        return $this->in('')->get();
    }

    /**
     * @example Query files in directory
     * @source PHPFile::in('app/HTTP')
     */
    public function in($directory)
    {
        $this->baseDir = $directory;

        $this->result = collect($this->recursiveFileSearch($this->baseDir))
            ->map(function ($filePath) {
                $type = class_basename($this->file);
                return app()->make($type)->load($filePath);
            });

        return $this;
    }

    /**
     * @example Where file->endpoint Equals value
     * @source PHPFile::where('className', 'User')
     *
     * @example Where file->endpoints <operator> value
     * @source PHPFile::where('implements', 'contains', 'MyInterface')
     *
     * @example Multiple conditions with array
     * @source PHPFile::where([['className', 'User'], ['use', 'includes', 'SomeClass']])
     *
     * @example Where callback returns true
     * @source PHPFile::where(fn($file) => $file->canUseReflection())
     */
    public function where($arg1, $arg2 = null, $arg3 = null)
    {
        // Ensure we are in a directory context - default to base path
        if (!isset($this->baseDir)) {
            $this->in('');
        }

        // If an array is passed
        if (is_array($arg1)) {
            collect($arg1)->each(function ($clause) {
                $this->where(...$clause);
            });
            return $this;
        }

        // If a function is passed
        if (is_callable($arg1)) {
            $this->result = $this->result->filter($arg1);
            return $this;
        }

        // If its a resource where query
        $property = $arg1;
        $operator = $arg3 ? $arg2 : "=";
        $value = $arg3 ? $arg3 : $arg2;

        if (!$this->operatorMethod($operator)) {
            throw new InvalidArgumentException("Operator not supported");
        }

        // Dispatch to HasOperators trait method
        $this->result = $this->result->filter(function ($file) use ($property, $operator, $value) {
            $operatorMethod = $this->operatorMethod($operator);

            return $this->$operatorMethod(
                $file->$property(),
                $value
            );
        });

        return $this;
    }

    /**
     * @example andWhere is an alias to where
     * @source PHPFile::where(...)->andWhere(...)->get()
     */
    public function andWhere(...$args)
    {
        return $this->where(...$args);
    }

    /**
     * @example Get a collection with results
     * @source PHPFile::where(...)->get()
     */
    public function get()
    {
        // Ensure we are in a directory context - default to base path
        if (!isset($this->baseDir)) {
            $this->in('');
        }
        return $this->result;
    }

    /**
     * @example Get the first match
     * @source PHPFile::where(...)->first()
     */
    public function first()
    {
        return $this->get()->first();
    }

    public function recursiveFileSearch($directory)
    {
        $directory = base_path($directory);

        return RecursiveFileSearch::in($directory)
            ->matching(static::PHPSignature)
            ->ignore(config('Pkeogan.ignored_paths'))
            ->get();
    }

    /** this is kept probably because of some inheritance issue */
    protected function getHandlerMethod($signature, $args)
    {
        $reflection = new ReflectionClass(static::class);
        $methods = collect($reflection->getMethods(ReflectionMethod::IS_PUBLIC))->pluck('name');
        return collect($methods)->contains($signature) ? $signature : false;
    }
}
