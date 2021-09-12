<?php

namespace Pkeogan\Factories;

use Pkeogan\PHPFile;

class PHPFileFactory
{
    const FILE_TYPE = PHPFile::class;

    public function __call($method, $args)
    {
        return self::__makeFileInstance()->$method(...$args);
    }

    public static function __callStatic($method, $args)
    {
        return self::__makeFileInstance()->$method(...$args);
    }

    protected static function __makeFileInstance()
    {
        $class = static::FILE_TYPE;
        $instance = new $class;
        $instance->inputDriver(self::__driver('input'));

        $instance->outputDriver(self::__driver('output'));

        return $instance;
    }

    protected static function __driver($name)
    {
        $driver = [
            "input" => config('Pkeogan.input', \Pkeogan\Drivers\FileInput::class),
            "output" => config('Pkeogan.output', \Pkeogan\Drivers\FileOutput::class),
        ][$name];

        return new $driver;
    }
}
