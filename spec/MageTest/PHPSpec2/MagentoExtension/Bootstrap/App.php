<?php

namespace spec\MageTest\PHPSpec2\MagentoExtension\Bootstrap;

use PHPSpec2\ObjectBehavior;

class App extends ObjectBehavior
{
    function let()
    {
        $app->beAMockOf('MageTest\PHPSpec2\MagentoExtension\Bootstrap\App');
        $this->beConstructedWith($app);
    }
}