<?php

namespace spec\MageTest\PHPSpec2\MagentoExtension;

use PHPSpec2\ObjectBehavior;

class Extension extends ObjectBehavior
{
    /**
     * @param PHPSpec2\Console\Application $application
     */
    public function it_should_call_extend_of_application($application)
    {
        $application->extend(ANY_ARGUMENTS)->shouldBeCalled();
        $this->setApplication($application);
        $this->extend();
    }
}
