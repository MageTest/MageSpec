<?php

namespace spec\MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml;

use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\Element\BlockElement;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\Element\HelperElement;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\Element\ModelElement;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\Element\SimpleElement;
use PhpSpec\ObjectBehavior;
use PhpSpec\Util\Filesystem;
use PrettyXml\Formatter;
use Prophecy\Argument;

class ConfigGeneratorSpec extends ObjectBehavior
{
    private $path = 'public/app/code/';

    function let(Filesystem $fileSystem, Formatter $formatter)
    {
        $this->beConstructedWith($this->path, $fileSystem, $formatter);
        $this->path .= 'local/';
    }

    function it_does_not_create_a_block_element_when_one_exists($fileSystem)
    {
        $fileSystem->pathExists($this->path . 'Vendor/Module/etc/config.xml')->willReturn(true);
        $fileSystem->getFileContents($this->path . 'Vendor/Module/etc/config.xml')
            ->willReturn($this->getBlockXmlStructure());
        $fileSystem->putFileContents(Argument::any())->shouldNotBeCalled();
        $this->addElementGenerator(new BlockElement());
        $this->generateElement('block', 'Vendor_Module');

    }

    function it_creates_the_etc_directory_if_it_does_not_exist($fileSystem, $formatter)
    {
        $fileSystem->isDirectory($this->path . 'Vendor/Module/etc/')->willReturn(false);
        $fileSystem->makeDirectory($this->path . 'Vendor/Module/etc/')->shouldBeCalled();
        $fileSystem->pathExists($this->path . 'Vendor/Module/etc/config.xml')->willReturn(false);
        $fileSystem->getFileContents(Argument::any())->shouldNotBeCalled();
        $formatter->format(Argument::containingString(
            '<blocks><vendor_module><class>Vendor_Module_Block</class></vendor_module></blocks>'
        ))->willReturn($this->getBlockXmlStructure());
        $fileSystem->putFileContents(
            $this->path . 'Vendor/Module/etc/config.xml',
            $this->getBlockXmlStructure()
        )->shouldBeCalled();
        $this->addElementGenerator(new BlockElement());
        $this->generateElement('block', 'Vendor_Module');
    }

    function it_generates_a_block_element_when_the_config_file_does_not_exist($fileSystem, $formatter)
    {
        $fileSystem->isDirectory($this->path . 'Vendor/Module/etc/')->willReturn(true);
        $fileSystem->pathExists($this->path . 'Vendor/Module/etc/config.xml')->willReturn(false);
        $fileSystem->getFileContents(Argument::any())->shouldNotBeCalled();
        $formatter->format(Argument::containingString(
            '<blocks><vendor_module><class>Vendor_Module_Block</class></vendor_module></blocks>'
        ))->willReturn($this->getBlockXmlStructure());
        $fileSystem->putFileContents(
            $this->path . 'Vendor/Module/etc/config.xml',
            $this->getBlockXmlStructure()
        )->shouldBeCalled();
        $this->addElementGenerator(new BlockElement());
        $this->generateElement('block', 'Vendor_Module');
    }

    function it_generates_a_block_element($fileSystem, $formatter)
    {
        $fileSystem->isDirectory($this->path . 'Vendor/Module/etc/')->willReturn(true);
        $fileSystem->pathExists($this->path . 'Vendor/Module/etc/config.xml')->willReturn(true);
        $fileSystem->getFileContents($this->path . 'Vendor/Module/etc/config.xml')
            ->willReturn($this->getPlainXmlStructure());
        $formatter->format(Argument::containingString(
            '<blocks><vendor_module><class>Vendor_Module_Block</class></vendor_module></blocks>'
        ))->willReturn($this->getBlockXmlStructure());
        $fileSystem->putFileContents(
            $this->path . 'Vendor/Module/etc/config.xml',
            $this->getBlockXmlStructure()
        )->shouldBeCalled();
        $this->addElementGenerator(new BlockElement());
        $this->generateElement('block', 'Vendor_Module');
    }

    function it_generates_a_model_element($fileSystem, $formatter)
    {
        $fileSystem->isDirectory($this->path . 'Vendor/Module/etc/')->willReturn(true);
        $fileSystem->pathExists($this->path . 'Vendor/Module/etc/config.xml')->willReturn(true);
        $fileSystem->getFileContents($this->path . 'Vendor/Module/etc/config.xml')
            ->willReturn($this->getPlainXmlStructure());
        $formatter->format(Argument::containingString(
            '<models><vendor_module><class>Vendor_Module_Model</class></vendor_module></models>'
        ))->willReturn($this->getModelXmlStructure());
        $fileSystem->putFileContents(
            $this->path . 'Vendor/Module/etc/config.xml',
            $this->getModelXmlStructure()
        )->shouldBeCalled();
        $this->addElementGenerator(new ModelElement());
        $this->generateElement('model', 'Vendor_Module');
    }

    function it_generates_a_helper_element($fileSystem, $formatter)
    {
        $fileSystem->isDirectory($this->path . 'Vendor/Module/etc/')->willReturn(true);
        $fileSystem->pathExists($this->path . 'Vendor/Module/etc/config.xml')->willReturn(true);
        $fileSystem->getFileContents($this->path . 'Vendor/Module/etc/config.xml')
            ->willReturn($this->getPlainXmlStructure());
        $formatter->format(Argument::containingString(
            '<helpers><vendor_module><class>Vendor_Module_Helper</class></vendor_module></helpers>'
        ))->willReturn($this->getHelperXmlStructure());
        $fileSystem->putFileContents(
            $this->path . 'Vendor/Module/etc/config.xml',
            $this->getHelperXmlStructure()
        )->shouldBeCalled();
        $this->addElementGenerator(new HelperElement());
        $this->generateElement('helper', 'Vendor_Module');
    }
/**
    function it_generates_a_resource_model_element($fileSystem, $formatter)
    {
        $fileSystem->isDirectory($this->path . 'Vendor/Module/etc/')->willReturn(true);
        $fileSystem->pathExists($this->path . 'Vendor/Module/etc/config.xml')->willReturn(true);
        $fileSystem->getFileContents($this->path . 'Vendor/Module/etc/config.xml')
            ->willReturn($this->getPlainXmlStructure());
        $formatter->format(Argument::containingString(
            '<models><vendor_module_resource><class>Vendor_Module_Model_Resource</class></vendor_module_resource></models>'
        ))->willReturn($this->getResourceModelXmlStructure());
        $fileSystem->putFileContents(
            $this->path . 'Vendor/Module/etc/config.xml',
            $this->getResourceModelXmlStructure()
        )->shouldBeCalled();
        $this->generateElement('resource_model', 'Vendor_Module');
    }
*/
    private function getPlainXmlStructure()
    {
        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<config>
    <modules>
        <Vendor_Module>
            <version>0.1.0</version>
        </Vendor_Module>
    </modules>
    <global>
    </global>
</config>
XML;
    }

    private function getBlockXmlStructure()
    {
        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<config>
    <modules>
        <Vendor_Module>
            <version>0.1.0</version>
        </Vendor_Module>
    </modules>
    <global>
        <blocks>
            <vendor_module>
                <class>Vendor_Module_Block</class>
            </vendor_module>
        </blocks>
    </global>
</config>
XML;
    }

    private function getModelXmlStructure()
    {
        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<config>
    <modules>
        <Vendor_Module>
            <version>0.1.0</version>
        </Vendor_Module>
    </modules>
    <global>
        <models>
            <vendor_module>
                <class>Vendor_Module_Model</class>
            </vendor_module>
        </models>
    </global>
</config>
XML;
    }

    private function getResourceModelXmlStructure()
    {
        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<config>
    <modules>
        <Vendor_Module>
            <version>0.1.0</version>
        </Vendor_Module>
    </modules>
    <global>
        <models>
            <vendor_module_resource>
                <class>Vendor_Module_Model_Resource</class>
            </vendor_module_resource>
        </models>
    </global>
</config>
XML;
    }

    private function getHelperXmlStructure()
    {
        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<config>
    <modules>
        <Vendor_Module>
            <version>0.1.0</version>
        </Vendor_Module>
    </modules>
    <global>
        <helpers>
            <vendor_module>
                <class>Vendor_Module_Helper</class>
            </vendor_module>
        </helpers>
    </global>
</config>
XML;
    }
}
