<?php

namespace spec\MageTest\PhpSpec\MagentoExtension\Locator\Magento;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use MageTest\PhpSpec\MagentoExtension\Locator\Magento\ModuleInitXmlLocator;

class ModuleInitXmlResourceSpec extends ObjectBehavior
{
    function let(ModuleInitXmlLocator $locator)
    {
        $this->beConstructedWith(array('VendorName', 'ModuleName', 'Model','ModelName'), $locator);
    }

    function it_is_a_resource()
    {
        $this->shouldBeAnInstanceOf('PhpSpec\Locator\ResourceInterface');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('MageTest\PhpSpec\MagentoExtension\Locator\Magento\ModuleInitXmlResource');
    }

    function it_uses_vendor_and_module_merged_with_underscores_as_name()
    {
        $this->getName()->shouldReturn('VendorName_ModuleName');
    }

    function it_generates_src_filename_from_provided_parts_using_locator($locator)
    {
        $locator->getFullSrcPath()->willReturn('/app/etc/modules/');
        $this->getSrcFilename()->shouldReturn('/app/etc/modules/VendorName_ModuleName.xml');
    }
}
