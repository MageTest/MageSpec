<?php

namespace spec\MageTest\PHPSpec2\MagentoExtension;

use PHPSpec2\ObjectBehavior;
use MageTest\PHPSpec2\MagentoExtension\Loader\SpecificationClassLoader as MageTestClassLoader;
use PHPSpec2\Loader\SpecificationsClassLoader as BaseClassLoader;


class Extension extends ObjectBehavior
{
    /**
     * @param  PHPSpec2\ServiceContainer $container
     */
    function it_should_replace_spec_loader($container)
    {
        $container->set(ANY_ARGUMENTS)->shouldBeCalled();

        $this->initialize($container);

        if (!$container->get('specification_loader') instanceof MageTestClassLoader) {
            throw new \Exception("The Specification loader has not been extended");
        }
    }

    /**
     * 3 Additional commands should be added to PHPSpec2
     *
     * @param  PHPSpec2\ServiceContainer $container
     */
    function it_should_add_console_commands($container)
    {
        $container->extend('console.commands', ANY_ARGUMENT)->shouldBeCalled();

        $this->initialize($container);
    }
}
