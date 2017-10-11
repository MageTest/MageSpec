<?php

namespace spec\MageTest\PhpSpec\MagentoExtension\Locator\Magento;

use PhpSpec\ObjectBehavior;
use MageTest\PhpSpec\MagentoExtension\Locator\Magento\BlockLocator;
use spec\MageTest\PhpSpec\DirectorySeparator;

class BlockResourceSpec extends ObjectBehavior
{
    function let(BlockLocator $locator)
    {
        $this->beConstructedWith(array('VendorName', 'ModuleName', 'Block', 'BlockName'), $locator);
    }

    function it_is_a_resource()
    {
        $this->shouldBeAnInstanceOf('PhpSpec\Locator\Resource');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('MageTest\PhpSpec\MagentoExtension\Locator\Magento\BlockResource');
    }

    function it_uses_all_the_segments_merged_with_underscores_as_name()
    {
        $this->getName()->shouldReturn('VendorName_ModuleName_Block_BlockName');
    }

    function it_appends_Spec_suffix_to_name_as_specName()
    {
        $this->getSpecName()->shouldReturn('VendorName_ModuleName_Block_BlockNameSpec');
    }

    function it_generates_src_filename_from_provided_parts_using_locator($locator)
    {
        $locator->getFullSrcPath()->willReturn(DirectorySeparator::replacePathWithDirectorySeperator('/app/code/local/'));
        $this->getSrcFilename()->shouldReturn(DirectorySeparator::replacePathWithDirectorySeperator('/app/code/local/VendorName/ModuleName/Block/BlockName.php'));
    }

    function it_should_return_empty_src_namespace($locator)
    {
        $locator->getSrcNamespace()->willReturn('');
        $this->getSrcNamespace()->shouldReturn('');
    }

    function it_generates_proper_src_classname()
    {
        $this->getSrcClassname()->shouldReturn('VendorName_ModuleName_Block_BlockName');
    }

    function it_generates_spec_filename_from_provided_parts_using_locator($locator)
    {
        $locator->getFullSpecPath()->willReturn(DirectorySeparator::replacePathWithDirectorySeperator('/spec/'));

        $this->getSpecFilename()->shouldReturn(DirectorySeparator::replacePathWithDirectorySeperator('/spec/VendorName/ModuleName/Block/BlockNameSpec.php'));
    }

    function it_should_return_spec_as_spec_namespace($locator)
    {
        $locator->getSpecNamespace()->willReturn('spec');
        $this->getSpecNamespace()->shouldReturn('spec');
    }

    function it_generates_spec_classname_from_provided_parts_using_locator($locator)
    {
        $locator->getSpecNamespace()->willReturn('spec\\');

        $this->getSpecClassname()->shouldReturn('spec\VendorName_ModuleName_Block_BlockNameSpec');
    }
}
