<?php

namespace Pkeogan\Generators;

use Pkeogan\Generators\BaseGenerator;
use Pkeogan\Schema\SimpleSchema\SimpleSchema;

class Migration extends BaseGenerator
{
    public function qualifies()
    {
        return false;
    }
        
    public function build()
    {
    }
}
