<?php

namespace Pkeogan\Endpoints\PHP;

use Pkeogan\Endpoints\EndpointProvider;

class ClassName extends EndpointProvider
{
    /**
     * @example Get file class name
     * @source $file->className()
     *
     * @example Get full class name
     * @source $file->full()->className()
     *
     * @example Set file class name
     * @source $file->className('MyClass')
     *
     * @param string $name
     * @return mixed
     */
    public function className($name = null)
    {
        if ($name === null) {
            return $this->get();
        }

        return $this->set($name);
    }

    protected function get()
    {
        $className = $this->file->astQuery()
            ->class()
            ->name
            ->name
            ->first();

        if (!$this->directive('full')) {
            return $className;
        }

        $namespace = $this->file->namespace();

        return $namespace ? "$namespace\\$className" : $className;
    }

    protected function set($newClassName)
    {
        return $this->file->astQuery()
            ->class()
            ->name
            ->replaceProperty('name', $newClassName)
            ->commit()
            ->end()
            ->continue();
    }
}
