<?php

namespace spec\MageTest\PhpSpec\MagentoExtension\Extension;

use MageTest\PhpSpec\MagentoExtension\Extension\LocatorFactory;
use MageTest\PhpSpec\MagentoExtension\Locator\Magento\BlockLocator;
use MageTest\PhpSpec\MagentoExtension\Locator\Magento\ControllerLocator;
use MageTest\PhpSpec\MagentoExtension\Locator\Magento\HelperLocator;
use MageTest\PhpSpec\MagentoExtension\Locator\Magento\ModelLocator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LocatorFactorySpec extends ObjectBehavior
{
    function it_should_get_block_locator()
    {
        $this->getLocator('block')->shouldHaveType(BlockLocator::class);
    }

    function it_should_get_model_locator()
    {
        $this->getLocator('model')->shouldHaveType(ModelLocator::class);
    }

    function it_should_get_helper_locator()
    {
        $this->getLocator('helper')->shouldHaveType(HelperLocator::class);
    }

    function it_should_get_controller_locator()
    {
        $this->getLocator('controller')->shouldHaveType(ControllerLocator::class);
    }

    function it_should_throw_exception_when_an_unrecognised_type_is_used()
    {
        $this->shouldThrow(\RuntimeException::class)->duringGetLocator('foo');
    }
}
