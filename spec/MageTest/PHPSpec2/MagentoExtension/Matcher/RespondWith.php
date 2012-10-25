<?php

namespace spec\MageTest\PHPSpec2\MagentoExtension\Matcher;

use PHPSpec2\ObjectBehavior;

class RespondWith extends ObjectBehavior
{
    function it_should_exist()
    {
        $this->object->shouldNotBe(null);
    }
}