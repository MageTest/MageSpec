<?php

namespace spec\MageTest\PhpSpec\MagentoExtension\Locator\Magento;

use PhpSpec\ObjectBehavior;
use MageTest\PhpSpec\MagentoExtension\Locator\Magento\ModelLocator;

class ModelResourceSpec extends ObjectBehavior
{
    function let(ModelLocator $locator)
    {
        $this->beConstructedWith(array('VendorName', 'ModuleName', 'Model','ModelName'), $locator);
    }

    function it_is_a_resource()
    {
        $this->shouldBeAnInstanceOf('PhpSpec\Locator\ResourceInterface');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('MageTest\PhpSpec\MagentoExtension\Locator\Magento\ModelResource');
    }

    function it_uses_all_the_segments_merged_with_underscores_as_name()
    {
        $this->getName()->shouldReturn('VendorName_ModuleName_Model_ModelName');
    }

    function it_appends_Spec_suffix_to_name_as_specName()
    {
        $this->getSpecName()->shouldReturn('VendorName_ModuleName_Model_ModelNameSpec');
    }

    function it_generates_src_filename_from_provided_parts_using_locator($locator)
    {
        $locator->getFullSrcPath()->willReturn('/app/code/local/');
        $this->getSrcFilename()->shouldReturn('/app/code/local/VendorName/ModuleName/Model/ModelName.php');
    }

    function it_should_return_empty_src_namespace($locator)
    {
        $locator->getSrcNamespace()->willReturn('');
        $this->getSrcNamespace()->shouldReturn('');
    }

    function it_generates_proper_src_classname()
    {
        $this->getSrcClassname()->shouldReturn('VendorName_ModuleName_Model_ModelName');
    }

    function it_generates_spec_filename_from_provided_parts_using_locator($locator)
    {
        $locator->getFullSpecPath()->willReturn('/spec/');

        $this->getSpecFilename()->shouldReturn('/spec/VendorName/ModuleName/Model/ModelNameSpec.php');
    }

    function it_should_return_spec_as_spec_namespace($locator)
    {
        $locator->getSpecNamespace()->willReturn('spec');
        $this->getSpecNamespace()->shouldReturn('spec');
    }

    function it_generates_spec_classname_from_provided_parts_using_locator($locator)
    {
        $locator->getSpecNamespace()->willReturn('spec\\');

        $this->getSpecClassname()->shouldReturn('spec\VendorName_ModuleName_Model_ModelNameSpec');
    }
}
