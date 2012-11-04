<?php

namespace spec\MageTest\PHPSpec2\MagentoExtension\Loader;

use PHPSpec2\ObjectBehavior;

class SpecificationsClassLoader extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('MageTest\PHPSpec2\MagentoExtension\Loader\SpecificationClassLoader');
    }
}
