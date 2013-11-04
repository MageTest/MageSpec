<?php

namespace MageTest\PhpSpec\MagentoExtension\Wrapper;
use PhpSpec\Exception\Exception;

class MethodNotFoundException extends Exception
{
    public function __construct($message = "", $object, $method, $args)
    {
    }
}
