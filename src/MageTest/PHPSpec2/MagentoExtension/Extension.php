<?php

namespace MageTest\PHPSpec2\MagentoExtension;

use PHPSpec2\Extension\ExtensionInterface,
    PHPSpec2\Console\ExtendableApplicationInterface as ApplicationInterface,
    PHPSpec2\Configuration\Configuration,
    PHPSpec2\Runner\Locator;

use MageTest\PHPSpec2\MagentoExtension\Loader\SpecificationsClassLoader;
use PHPSpec2\ServiceContainer;

class Extension implements ExtensionInterface
{
    private $application;

    public function initialize(ServiceContainer $container)
    {
        $container->set('specifications_loader', $container->share(function($c) {
            return new SpecificationsClassLoader;
        }));

        $container->set('locator', $container->share(function($c) {
            return new Locator($c('specifications_loader'));
        }));
    }
}
