<?php

namespace spec\MageTest\PhpSpec\MagentoExtension\Locator\Magento;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HelperLocatorSpec extends ObjectBehavior
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
        $this->shouldHaveType('MageTest\PhpSpec\MagentoExtension\Locator\Magento\HelperLocator');
    }

    function its_priority_is_twenty()
    {
        $this->getPriority()->shouldReturn(20);
    }

    function it_supports_helper_query_with_helper_name()
    {
        $this->supportsQuery('helper:vendor_module/helper')->shouldReturn(true);
    }

    function it_supports_helper_query_with_subhelper_name()
    {
        $this->supportsQuery('helper:vendor_module/helper_subhelper')->shouldReturn(true);

    }

    function it_supports_helper_query_with_multiple_subhelper_name()
    {
        $this->supportsQuery('helper:vendor_module/helper_subhelper_subhelper_subhelper_subhelper')->shouldReturn(true);
    }

    function it_does_not_support_helper_query_without_vendor_name()
    {
        $this->supportsQuery('helper:module/model')->shouldReturn(false);
    }

    function it_does_not_support_helper_query_without_module_name()
    {
        $this->supportsQuery('helper:vendor/model')->shouldReturn(false);
    }

    function it_does_not_support_helper_query_without_helper_name()
    {
        $this->supportsQuery('helper:vendor_module')->shouldReturn(false);
    }

    function it_does_not_support_malformed_query()
    {
        $this->supportsQuery('malformed_module_model_definition')->shouldReturn(false);
    }
}
