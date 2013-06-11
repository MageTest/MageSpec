<?php

namespace spec\MageTest\PhpSpec\MagentoExtension\Locator\Magento;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ControllerLocatorSpec extends ObjectBehavior
{
    private $srcPath;
    private $specPath;

    function let()
    {
        $this->srcPath  = realpath(__DIR__.'/../../../../../../src');
        $this->specPath = realpath(__DIR__.'/../../../../../');
    }

    function it_is_a_locator()
    {
        $this->shouldBeAnInstanceOf('PhpSpec\Locator\ResourceLocatorInterface');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('MageTest\PhpSpec\MagentoExtension\Locator\Magento\ControllerLocator');
    }

    function its_priority_is_ten()
    {
        $this->getPriority()->shouldReturn(10);
    }

    function it_supports_controller_query_with_controller_name()
    {
        $this->supportsQuery('controller:vendor_module/controller')->shouldReturn(true);
    }

    function it_does_not_supports_controller_query_with_subcontroller_name()
    {
        $this->supportsQuery('controller:vendor_module/controller')->shouldReturn(true);

    }

    function it_does_not_support_controller_query_without_vendor_name()
    {
        $this->supportsQuery('controller:module/controller')->shouldReturn(false);
    }

    function it_does_not_support_block_query_without_module_name()
    {
        $this->supportsQuery('controller:vendor/controlelr')->shouldReturn(false);
    }

    function it_does_not_support_controller_query_without_controller_name()
    {
        $this->supportsQuery('controller:vendor_module')->shouldReturn(false);
    }

    function it_does_not_support_malformed_query()
    {
        $this->supportsQuery('malformed_module_model_definition')->shouldReturn(false);
    }
}
