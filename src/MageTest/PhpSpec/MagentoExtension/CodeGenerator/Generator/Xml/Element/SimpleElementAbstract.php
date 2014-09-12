<?php

namespace MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\Element;


use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\XmlGeneratorException;

abstract class SimpleElementAbstract
{
    /**
     * @param \SimpleXMLElement $xml
     * @param string $type
     * @param string $moduleName
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
            throw new XmlGeneratorException(sprintf('Global element not found in %s config file', $moduleName));
        }

        $globalElements[0]->addChild($type.'s')
            ->addChild(strtolower($moduleName))
            ->addChild('class', $moduleName . '_' . ucfirst($type));
    }

    /**
     * @param \SimpleXMLElement $xml
     * @param string $path
     * @return \SimpleXMLElement
     */
    protected function getElement(\SimpleXMLElement $xml, $path)
    {
        $elements = $xml->xpath($path);

        if (!count($elements)) {
            return $xml->addChild($path);
        }

        return $elements[0];
    }
} 