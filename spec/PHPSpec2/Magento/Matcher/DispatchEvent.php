<?php

namespace spec\PHPSpec2\Magento\Matcher;

use PHPSpec2\Specification;

class DispatchEvent implements Specification
{
    function it_should_exist()
    {
        $this->object->shouldNotBe(null);
    }
}