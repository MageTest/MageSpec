<?php

namespace spec\PHPSpec2\Magento\Bootstrap;

use PHPSpec2\Specification;

class App implements Specification
{
    function it_should_exist()
    {
        $this->object->shouldNotBe(null);
    }
}