<?php

namespace spec\MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml;

use PhpSpec\Locator\ResourceInterface;
use PhpSpec\ObjectBehavior;
use PhpSpec\Util\Filesystem;
use Prophecy\Argument;

class ModuleGeneratorSpec extends ObjectBehavior
{
    private $fileSystem;
    private $path = 'public/app/etc/modules/';

    function let(Filesystem $fileSystem)
    {
        $this->fileSystem = $fileSystem;
        $this->beConstructedWith($this->fileSystem, $this->path);
    }

    function it_checks_if_the_module_file_exists()
    {
        $this->fileSystem->pathExists('public/app/etc/modules/Vendor_Module.xml')->willReturn(true);
        $this->moduleFileExists('Vendor_Module')->shouldReturn(true);
    }

    function it_generates_the_module_xml_file()
    {
        $this->fileSystem->putFileContents(
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
}
