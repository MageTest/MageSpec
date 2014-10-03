<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MyVendor_MyModule_Blocks_Directory_Spec extends ObjectBehavior
{
    function let(\MyVendor_MyModule_Models_Adapters_ConfigAdapter $adapter)
    {
        $this->beConstructedWith(array('config_adapter' => $adapter));
    }

    function it_should_return_the_base_url($adapter)
    {
        $adapter->getBaseDir()->willReturn('http://www.google.com/');
        $this->getBaseDirectory()->shouldReturn('http://www.google.com/');
    }
} 
