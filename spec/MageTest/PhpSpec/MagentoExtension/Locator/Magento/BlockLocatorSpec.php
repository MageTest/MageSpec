<?php

namespace spec\MageTest\PhpSpec\MagentoExtension\Locator\Magento;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BlockLocatorSpec extends ObjectBehavior
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
        $this->shouldHaveType('MageTest\PhpSpec\MagentoExtension\Locator\Magento\BlockLocator');
    }

    function its_priority_is_thirty()
    {
        $this->getPriority()->shouldReturn(30);
    }

    function it_supports_block_query_with_block_name()
    {
        $this->supportsQuery('block:vendor_module/block')->shouldReturn(true);
    }

    function it_supports_block_query_with_subblock_name()
    {
        $this->supportsQuery('block:vendor_module/block_subblock')->shouldReturn(true);

    }

    function it_supports_block_query_with_multiple_subblock_name()
    {
        $this->supportsQuery('block:vendor_module/block_subblock_subblock_subblock_subblock')->shouldReturn(true);
    }

    function it_does_not_support_block_query_without_vendor_name()
    {
        $this->supportsQuery('block:module/model')->shouldReturn(false);
    }

    function it_does_not_support_block_query_without_module_name()
    {
        $this->supportsQuery('block:vendor/model')->shouldReturn(false);
    }

    function it_does_not_support_block_query_without_block_name()
    {
        $this->supportsQuery('block:vendor_module')->shouldReturn(false);
    }

    function it_does_not_support_malformed_query()
    {
        $this->supportsQuery('malformed_module_model_definition')->shouldReturn(false);
    }
}
