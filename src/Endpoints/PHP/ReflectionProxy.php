<?php

namespace Pkeogan\Endpoints\PHP;

use Pkeogan\Endpoints\EndpointProvider;
use ReflectionClass;
use Exception;

class ReflectionProxy extends EndpointProvider
{
    /**
     * @example Get ReflectionClass
     * @source $file->getReflection()
     *
     * @return mixed
     */
    public function getReflection()
    {
        $class = "\\" . $this->file->namespace() ."\\" . $this->file->className();

        try {
            return $class ? new ReflectionClass($class) : null;
        } catch (Exception $e) {
            return null;
        }
    }
}
