<?php

namespace spec\MageTest\PHPSpec2\MagentoExtension\Loader;

use PHPSpec2\ObjectBehavior;

class SpecificationLoader extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('MageTest\PHPSpec2\MagentoExtension\Loader\SpecificationLoader');
    }
}
