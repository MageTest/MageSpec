<?php

namespace spec\MageTest\PHPSpec2\MagentoExtension;

use PHPSpec2\ObjectBehavior;
use MageTest\PHPSpec2\MagentoExtension\Loader\SpecificationClassLoader as MageTestClassLoader;
use PHPSpec2\Loader\SpecificationsClassLoader as BaseClassLoader;


class Extension extends ObjectBehavior
{
    /**
     * @param  PHPSpec2\ServiceContainer $container
     * @param  MageTest\PHPSpec2\MagentoExtension\Loader\SpecificationClassLoader $specClassLoader
     */
    function it_should_replace_spec_loader($container, $specClassLoader)
    {
        $container->share(ANY_ARGUMENTS)->shouldBeCalled()->willReturn($specClassLoader);
        $container->set('specifications_loader', $specClassLoader)->shouldBeCalled();

        $this->initialize($container);
    }
}
