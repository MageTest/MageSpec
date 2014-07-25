<?php

namespace MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\Element;


interface ConfigElementInterface
{
    /**
     * @param string $type
     * @return boolean
     */
    public function supports($type);

    /**
     * @param \SimpleXMLElement $xml
     * @param string $type
     * @param string $moduleName
     * @return \SimpleXmlElement
     */
    public function addElementToXml(\SimpleXMLElement $xml, $type, $moduleName);

    /**
     * @param \SimpleXMLElement $xml
     * @param string $type
     * @param string $moduleName
     * @return boolean
     */
    public function elementExistsInXml(\SimpleXMLElement $xml, $type, $moduleName);
} 