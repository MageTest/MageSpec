<?php

namespace MageTest\PHPSpec2\MagentoExtension;

use PHPSpec2\Extension\ExtensionInterface,
    PHPSpec2\Console\ExtendableApplicationInterface as ApplicationInterface,
    PHPSpec2\Configuration\Configuration;

use MageTest\PHPSpec2\MagentoExtension\Loader\SpecificationClassLoader;
use MageTest\PHPSpec2\MagentoExtension\Command\DescribeModelCommand;
use MageTest\PHPSpec2\MagentoExtension\Command\DescribeControllerCommand;
use PHPSpec2\ServiceContainer;

class Extension implements ExtensionInterface
{
    private $application;
    private $configuration;

    public function initialize(ServiceContainer $container)
    {
        $container->set('specifications_loader', $container->share(function($c) {
            return new SpecificationClassLoader;
        }));

        $container->extend('console.commands', function($container) {
            return new DescribeControllerCommand;
        });
        $container->extend('console.commands', function($container) {
            return new DescribeModelCommand;
        });
    }
}
