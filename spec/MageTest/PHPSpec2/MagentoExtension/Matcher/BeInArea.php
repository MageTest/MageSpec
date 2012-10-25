<?php

namespace spec\MageTest\PHPSpec2\MagentoExtension\Matcher;

use PHPSpec2\ObjectBehavior;

class BeInArea extends ObjectBehavior
{
    function let($presenter)
    {
        $presenter->beAMockOf('PHPSpec2\Formatter\Presenter\PresenterInterface');
        $this->beConstructedWith($presenter);
    }

    /**
     * @param Mage_Core_Controller_Front_Action $controller
     */
    function it_should_support_be_in_area_for_controllers($controller)
    {
        $this->supports('beInArea', $controller, array('admin'))
             ->shouldBe(true);
    }
}