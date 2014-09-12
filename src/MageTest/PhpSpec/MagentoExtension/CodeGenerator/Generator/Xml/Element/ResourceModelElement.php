<?php

namespace MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\Element;

use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\XmlGeneratorException;

class ResourceModelElement extends SimpleElementAbstract implements ConfigElementInterface
{
    /**
     * @param string $type
     * @return boolean
     */
    public function supports($type)
    {
        return $type === 'resource_model';
    }

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
        $classElements = $xml->xpath('/config/global/'.$type.'s'.'/'.strtolower($moduleName).'_resource/class');
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

        $modelsElement = $this->getElement($globalElements[0], 'models');
        $modelsElement->addChild(strtolower($moduleName).'_resource')
            ->addChild('class', $moduleName . '_Model_Resource');

        $modelsClassContainers = $modelsElement->xpath(strtolower($moduleName));

        if (count($modelsClassContainers) && count($modelsClassContainers[0]->xpath('class'))) {
            $modelsClassContainers[0]->addChild('resourceModel', strtolower($moduleName).'_resource');
        }
    }
}
