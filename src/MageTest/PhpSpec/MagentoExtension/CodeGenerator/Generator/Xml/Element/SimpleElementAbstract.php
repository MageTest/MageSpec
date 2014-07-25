<?php

namespace MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\Element;


use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\XmlGeneratorException;

abstract class SimpleElementAbstract
{
    /**
     * @param \SimpleXMLElement $xml
     * @param string $type
     * @return bool
     */
    public function elementExistsInXml(\SimpleXMLElement $xml, $type, $moduleName)
    {
        $targetElements = $xml->xpath('/config/global/'.$type.'s');
        if (!count($targetElements)) {
            return false;
        }
        $classElements = $xml->xpath('/config/global/'.$type.'s'.'/'.strtolower($moduleName).'/class');
        return (bool) count($classElements);
    }

    /**
     * @param \SimpleXMLElement $xml
     * @param string $type
     * @param string $moduleName
     * @throws XmlGeneratorException
     * @return null
     */
    public function addElementToXml(\SimpleXMLElement $xml, $type, $moduleName)
    {
        $globalElements = $xml->xpath('/config/global');

        if (!count($globalElements)) {
            throw new XmlGeneratorException('Global element not found in ' . $this->getFilePath());
        }

        $globalElements[0]->addChild($type.'s')
            ->addChild(strtolower($moduleName))
            ->addChild('class', $moduleName . '_' . ucfirst($type));
    }
} 