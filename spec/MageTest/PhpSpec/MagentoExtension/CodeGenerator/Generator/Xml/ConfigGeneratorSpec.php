<?php

namespace spec\MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml;

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
        $this->generateElement('block', 'Vendor_Module');

    }

    function it_generates_a_block_element_when_the_config_file_does_not_exist($fileSystem, $formatter)
    {
        $fileSystem->pathExists($this->path . 'Vendor/Module/etc/config.xml')->willReturn(false);
        $fileSystem->getFileContents(Argument::any())->shouldNotBeCalled();
        $formatter->format(Argument::containingString(
            '<blocks><vendor_module><class>Vendor_Module_Block</class></vendor_module></blocks>'
        ))->willReturn($this->getBlockXmlStructure());
        $fileSystem->putFileContents(
            $this->path . 'Vendor/Module/etc/config.xml',
            $this->getBlockXmlStructure()
        )->shouldBeCalled();
        $this->generateElement('block', 'Vendor_Module');
    }

    function it_generates_a_block_element($fileSystem, $formatter)
    {
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
        $this->generateElement('block', 'Vendor_Module');
    }

    function it_generates_a_model_element($fileSystem, $formatter)
    {
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
        $this->generateElement('model', 'Vendor_Module');
    }

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
        <modelss>
            <vendor_module>
                <class>Vendor_Module_Model</class>
            </vendor_module>
        </modelss>
    </global>
</config>
XML;
    }
}
