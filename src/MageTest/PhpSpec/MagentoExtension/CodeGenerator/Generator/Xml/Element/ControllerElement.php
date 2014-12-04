<?php

namespace MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\Element;

class ControllerElement extends SimpleElementAbstract implements ConfigElementInterface
{
    /**
     * @param string $type
     * @return boolean
     */
    public function supports($type)
    {
        return $type === 'controller';
    }

    /**
     * @param \SimpleXMLElement $xml
     * @param string $type
     * @param string $moduleName
     * @return \SimpleXmlElement
     */
    public function addElementToXml(\SimpleXMLElement $xml, $type, $moduleName)
    {
        $frontendElement = $this->getElement($xml, 'frontend');
        $routersElement = $this->getElement($frontendElement, 'routers');
        $moduleElement = $this->getElement($routersElement, $this->getModuleRouteName($moduleName));
        $moduleElement->addChild('use', 'standard');
        $argsElement = $moduleElement->addChild('args');
        $argsElement->addChild('module', $moduleName);
        $argsElement->addChild('frontName', $this->getModuleRouteName($moduleName));
    }

    /**
     * @param \SimpleXMLElement $xml
     * @param string $type
     * @param string $moduleName
     * @return boolean
     */
    public function elementExistsInXml(\SimpleXMLElement $xml, $type, $moduleName)
    {
        $targetElements = $xml->xpath('/config/frontend/routers/' . $this->getModuleRouteName($moduleName));
        return (bool) count($targetElements);
    }

    /**
     * @param string $moduleName
     * @return string
     */
    private function getModuleRouteName($moduleName)
    {
        $parts = explode('_', $moduleName);
        return strtolower($parts[1]);
    }
}
