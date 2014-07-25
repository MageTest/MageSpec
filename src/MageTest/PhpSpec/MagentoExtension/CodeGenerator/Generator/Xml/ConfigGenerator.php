<?php

namespace MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml;

use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\Element\ConfigElementInterface;
use PhpSpec\Util\Filesystem;
use PrettyXml\Formatter;

class ConfigGenerator
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var Formatter
     */
    private $formatter;

    /**
     * @var string
     */
    private $directory;

    /**
     * @var array
     */
    private $elementGenerators = array();

    public function __construct($path, Filesystem $filesystem, Formatter $formatter, $codePool = 'local')
    {
        $this->path = $path . $codePool . DIRECTORY_SEPARATOR;
        $this->filesystem = $filesystem;
        $this->formatter = $formatter;
    }

    public function addElementGenerator(ConfigElementInterface $elementGenerator)
    {
        $this->elementGenerators[] = $elementGenerator;
    }

    public function generateElement($type, $moduleName)
    {
        $this->directory = $this->getDirectoryPath($moduleName);

        $xml = new \SimpleXMLElement($this->getCurrentConfigXml($moduleName));

        foreach ($this->elementGenerators as $elementGenerator) {
            if ($elementGenerator->supports($type)) {
                if ($elementGenerator->elementExistsInXml($xml, $type, $moduleName)) {
                    return;
                }
                $elementGenerator->addElementToXml($xml, $type, $moduleName);
                $formatted = $this->getIndentedXml($xml);
                $this->writeConfigFile($formatted);
                return;
            }
        }

        throw new XmlGeneratorException('No element generator found for type: '.$type);
    }

    private function getDirectoryPath($moduleName)
    {
        $modulePath = str_replace('_', DIRECTORY_SEPARATOR, $moduleName);
        return $this->path . $modulePath . DIRECTORY_SEPARATOR . 'etc' . DIRECTORY_SEPARATOR;
    }

    private function getCurrentConfigXml($moduleName)
    {
        if (!$this->moduleFileExists($moduleName)) {
            $values = array(
                '%module_name%' => $moduleName
            );
            return strtr(file_get_contents(__FILE__, null, null, __COMPILER_HALT_OFFSET__), $values);
        }
        return $this->filesystem->getFileContents($this->getFilePath());
    }

    private function getFilePath()
    {
        return $this->directory . 'config.xml';
    }

    private function moduleFileExists()
    {
        return $this->filesystem->pathExists($this->getFilePath());
    }

    private function getIndentedXml(\SimpleXMLElement $xml)
    {
        return $this->formatter->format($xml->asXML());
    }

    private function writeConfigFile($xml)
    {
        if (!$this->filesystem->isDirectory($this->directory)) {
            $this->filesystem->makeDirectory($this->directory);
        }
        $this->filesystem->putFileContents($this->getFilePath(), $xml);
    }
}
__halt_compiler();<?xml version="1.0" encoding="UTF-8"?>
<config>
    <modules>
        <%module_name%>
            <version>0.1.0</version>
        </%module_name%>
    </modules>
    <global>
    </global>
</config>