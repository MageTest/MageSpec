<?php

namespace MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\Element;

class ControllerElement implements ConfigElementInterface
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
        $configElement = $this->getElement($xml, 'config');
        $frontendElement = $this->getElement($configElement, 'frontend');
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

    private function getElement(\SimpleXMLElement $xml, $path)
    {
        $elements = $xml->xpath($path);

        if (!count($elements)) {
            return $xml->addChild($path);
        }

        return $elements[0];
    }

    private function getModuleRouteName($moduleName)
    {
        $parts = explode('_', $moduleName);
        return strtolower($parts[1]);
    }
}
