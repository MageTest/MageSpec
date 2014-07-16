<?php

namespace MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\Element;

class HelperElement extends SimpleElementAbstract implements ConfigElementInterface
{
    /**
     * @param string $type
     * @return boolean
     */
    public function supports($type)
    {
        return $type === 'helper';
    }
}
