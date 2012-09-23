<?php

namespace spec\PHPSpec2\Magento\Matcher;

use PHPSpec2\Specification;

class BeInArea implements Specification
{
    /**
     * @param Prophet $controller mock of PHPSpec2\Magento\ControllerSpecification
     */
    function it_should_support_be_in_area_for_controllers($controller)
    {
        $this->beInArea->supports('beInArea', $controller, array('admin'))
             ->shouldBeTrue();
    }
}