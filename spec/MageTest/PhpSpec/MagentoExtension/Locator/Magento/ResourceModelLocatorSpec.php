<?php

namespace spec\MageTest\PhpSpec\MagentoExtension\Locator\Magento;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ResourceModelLocatorSpec extends ObjectBehavior
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
        $this->shouldHaveType('MageTest\PhpSpec\MagentoExtension\Locator\Magento\ResourceModelLocator');
    }

    function its_priority_is_forty_one()
    {
        $this->getPriority()->shouldReturn(41);
    }

    function it_supports_resource_model_query_with_resource_model_name()
    {
        $this->supportsQuery('resource_model:vendor_module/resourcemodel')->shouldReturn(true);
    }

    function it_supports_resource_model_query_with_submodel_name()
    {
        $this->supportsQuery('resource_model:vendor_module/resourcemodel_submodel')->shouldReturn(true);

    }

    function it_supports_resource_model_query_with_multiple_submodel_name()
    {
        $this->supportsQuery('resource_model:vendor_module/resourcemodel_submodel_submodel_submodel_submodel')->shouldReturn(true);
    }

    function it_does_not_support_resource_model_query_without_vendor_name()
    {
        $this->supportsQuery('resource_model:module/model')->shouldReturn(false);
    }

    function it_does_not_support_resource_model_query_without_module_name()
    {
        $this->supportsQuery('resource_model:vendor/model')->shouldReturn(false);
    }

    function it_does_not_support_resource_model_query_without_resource_model_name()
    {
        $this->supportsQuery('resource_model:vendor_module')->shouldReturn(false);
    }

    function it_does_not_support_malformed_query()
    {
        $this->supportsQuery('malformed_module_model_definition')->shouldReturn(false);
    }
}
