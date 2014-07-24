<?php

namespace MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\Element;

class BlockElement extends SimpleElementAbstract implements ConfigElementInterface
{
    /**
     * @param string $type
     * @return boolean
     */
    public function supports($type)
    {
        return $type === 'block';
    }
}
