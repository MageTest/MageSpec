<?php

namespace spec\PHPSpec2\Magento\Matcher;

use PHPSpec2\Specification;

class BeInArea implements Specification
{
    /**
     * @param Prophet $controller mock of \Mage_Core_Controller_Front_Action
     */
    function it_should_support_be_in_area_for_controllers($controller)
    {
        $this->beInArea->supports('beInArea', $controller, array('admin'))
             ->shouldBeTrue();
    }
}