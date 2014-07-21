<?php

namespace MageTest\PhpSpec\MagentoExtension\Util;


class ClassDetector
{
    public function classExists($className)
    {
        return class_exists($className);
    }
} 