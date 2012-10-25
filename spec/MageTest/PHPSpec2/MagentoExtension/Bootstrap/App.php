<?php

namespace spec\MageTest\PHPSpec2\MagentoExtension\Bootstrap;

use PHPSpec2\Specification;

class App implements Specification
{
    function it_should_exist()
    {
        $this->object->shouldNotBe(null);
    }
}