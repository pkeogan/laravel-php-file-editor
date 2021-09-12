<?php

namespace Pkeogan\Drivers;

interface OutputInterface
{
    public function save($path, $content);

    public function debug($path = null);
}
