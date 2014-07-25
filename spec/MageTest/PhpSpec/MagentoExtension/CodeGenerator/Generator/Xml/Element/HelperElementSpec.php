<?php

namespace spec\MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\Element;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HelperElementSpec extends ObjectBehavior
{
    function it_is_a_config_element()
    {
        $this->shouldImplement(
            'MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\Element\ConfigElementInterface'
        );
    }

    function it_should_support_the_helper_element()
    {
        $this->supports('helper')->shouldReturn(true);
    }
}
