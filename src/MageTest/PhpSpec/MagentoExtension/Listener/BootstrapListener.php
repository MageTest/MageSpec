<?php

namespace MageTest\PhpSpec\MagentoExtension\Listener;

use MageTest\PhpSpec\MagentoExtension\Autoloader\MageLoader;
use MageTest\PhpSpec\MagentoExtension\Configuration\MageLocator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BootstrapListener implements EventSubscriberInterface
{
    private $configuration;

    public function __construct(MageLocator $configuration)
    {
        $this->configuration = $configuration;
    }

    public static function getSubscribedEvents()
    {
        return ['beforeSuite' => ['beforeSuite', 1100]];
    }

    public function beforeSuite()
    {
        MageLoader::register(
            $this->configuration->getSrcPath(),
            $this->configuration->getCodePool()
        );
    }
}
