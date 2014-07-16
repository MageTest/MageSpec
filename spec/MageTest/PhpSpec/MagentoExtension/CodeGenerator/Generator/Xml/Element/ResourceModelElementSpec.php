<?php

namespace spec\MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\Element;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ResourceModelElementSpec extends ObjectBehavior
{
    function it_is_a_config_element()
    {
        $this->shouldImplement(
            'MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\Element\ConfigElementInterface'
        );
    }

    function it_should_support_the_model_element()
    {
        $this->supports('resource_model')->shouldReturn(true);
    }
}
