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

    public function __construct($path, Filesystem $filesystem, Formatter $formatter)
    {
        $this->path = $path . 'local' . DIRECTORY_SEPARATOR;
        $this->filesystem = $filesystem;
        $this->formatter = $formatter;
    }

    public function generateElement($type, $moduleName)
    {
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
        $this->filesystem->putFileContents($this->getFilePath($moduleName), $formatted);
    }

    private function getFilePath($moduleName)
    {
        $modulePath = str_replace('_', DIRECTORY_SEPARATOR, $moduleName);
        return $this->path . $modulePath . DIRECTORY_SEPARATOR . 'etc' . DIRECTORY_SEPARATOR . 'config.xml';
    }

    private function moduleFileExists($moduleName)
    {
        return $this->filesystem->pathExists($this->getFilePath($moduleName));
    }

    private function getIndentedXml(\SimpleXMLElement $xml)
    {
        return $this->formatter->format($xml->asXML());
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