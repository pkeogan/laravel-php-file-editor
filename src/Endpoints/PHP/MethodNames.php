<?php

namespace Pkeogan\Endpoints\PHP;

use Pkeogan\Endpoints\EndpointProvider;

class MethodNames extends EndpointProvider
{
    /**
     * @example Get class method names
     * @source $file->methodNames()
     */
    public function methodNames()
    {
        return $this->get();
    }

    protected function get()
    {
        return $this->file->astQuery()
            ->method()
            ->name
            ->name
            ->get()
            ->toArray();
    }
}
