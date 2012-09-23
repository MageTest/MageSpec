<?php

namespace PHPSpec2\Magento;

use Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Loader\XmlFileLoader,
    Symfony\Component\Config\FileLocator;

class ContainerAwareSpecification
{
    public function __construct()
    {
        $this->init();
    }

    public function get($service)
    {
        return $this->container->get($service);
    }

    public function init()
    {
        $this->container = new ContainerBuilder;
        $loader = new XmlFileLoader(
            $this->container,
            new FileLocator(
                __DIR__ . DIRECTORY_SEPARATOR . 'Container'
            )
        );

        $loader->load('services.xml');
    }
}