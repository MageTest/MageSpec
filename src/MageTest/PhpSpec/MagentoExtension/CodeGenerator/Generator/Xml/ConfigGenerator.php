<?php

namespace MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml;

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

    public function __construct($path, Filesystem $filesystem, Formatter $formatter)
    {
        $this->path = $path . 'local' . DIRECTORY_SEPARATOR;
        $this->filesystem = $filesystem;
        $this->formatter = $formatter;
    }

    public function generateElement($type, $moduleName)
    {
        $this->directory = $this->getDirectoryPath($moduleName);
        if (!$this->moduleFileExists($moduleName)) {
            $values = array(
                '%module_name%' => $moduleName
            );
            $rawXml = strtr(file_get_contents(__FILE__, null, null, __COMPILER_HALT_OFFSET__), $values);
        } else {
            $rawXml = $this->filesystem->getFileContents($this->getFilePath($moduleName));
        }

        $xml = new \SimpleXMLElement($rawXml);

        $targetElements = $xml->xpath('/config/global/'.$type.'s');
        if (count($targetElements)) {
            return;
        }

        $globalElements = $xml->xpath('/config/global');
        if (!count($globalElements)) {
            throw new XmlGeneratorException('Global element not found in ' . $this->getFilePath($moduleName));
        }

        $globalElements[0]->addChild($type.'s')
            ->addChild(strtolower($moduleName))
            ->addChild('class', $moduleName . '_' . ucfirst($type));

        $formatted = $this->getIndentedXml($xml);
        $this->writeConfigFile($moduleName, $formatted);
    }

    private function getDirectoryPath($moduleName)
    {
        $modulePath = str_replace('_', DIRECTORY_SEPARATOR, $moduleName);
        return $this->path . $modulePath . DIRECTORY_SEPARATOR . 'etc' . DIRECTORY_SEPARATOR;
    }

    private function getFilePath()
    {
        return $this->directory . 'config.xml';
    }

    private function moduleFileExists($moduleName)
    {
        return $this->filesystem->pathExists($this->getFilePath($moduleName));
    }

    private function getIndentedXml(\SimpleXMLElement $xml)
    {
        return $this->formatter->format($xml->asXML());
    }

    private function writeConfigFile($moduleName, $xml)
    {
        if (!$this->filesystem->isDirectory($this->directory)) {
            $this->filesystem->makeDirectory($this->directory);
        }
        $this->filesystem->putFileContents($this->getFilePath($moduleName), $xml);
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