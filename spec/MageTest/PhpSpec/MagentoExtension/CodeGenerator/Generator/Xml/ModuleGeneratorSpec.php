<?php

namespace spec\MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml;

use PhpSpec\Locator\ResourceInterface;
use PhpSpec\ObjectBehavior;
use PhpSpec\Util\Filesystem;
use Prophecy\Argument;

class ModuleGeneratorSpec extends ObjectBehavior
{
    private $path = 'public/app/etc/modules/';

    function let(Filesystem $fileSystem)
    {
        $this->beConstructedWith($this->path, $fileSystem);
    }

    function it_generates_the_module_xml_file_if_one_does_not_exist($fileSystem)
    {
        $fileSystem->pathExists('public/app/etc/modules/Vendor_Module.xml')->willReturn(false);
        $fileSystem->putFileContents(
            $this->path . 'Vendor_Module.xml',
            <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<config>
    <modules>
        <Vendor_Module>
            <active>true</active>
            <codePool>local</codePool>
        </Vendor_Module>
    </modules>
</config>
XML
        )->shouldBeCalled();
        $this->generate('Vendor_Module');
    }


    function it_generates_the_module_xml_file_in_a_different_code_pool($fileSystem)
    {
        $this->beConstructedWith($this->path, $fileSystem, 'community');
        $fileSystem->pathExists('public/app/etc/modules/Vendor_Module.xml')->willReturn(false);
        $fileSystem->putFileContents(
            $this->path . 'Vendor_Module.xml',
            <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<config>
    <modules>
        <Vendor_Module>
            <active>true</active>
            <codePool>community</codePool>
        </Vendor_Module>
    </modules>
</config>
XML
        )->shouldBeCalled();
        $this->generate('Vendor_Module');
    }

    function it_does_not_generate_the_module_xml_file_if_one_already_exists($fileSystem)
    {
        $fileSystem->pathExists('public/app/etc/modules/Vendor_Module.xml')->willReturn(true);
        $fileSystem->putFileContents(Argument::any())->shouldNotBeCalled();
        $this->generate('Vendor_Module');
    }
}
