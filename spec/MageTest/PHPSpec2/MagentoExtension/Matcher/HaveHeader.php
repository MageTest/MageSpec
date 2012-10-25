<?php

namespace spec\PHPSpec2\Magento\Matcher;

use PHPSpec2\Specification;

class HaveHeader implements Specification
{
    function it_should_exist()
    {
        $this->object->shouldNotBe(null);
    }
}