<?php

namespace MageTest\PHPSpec2\MagentoExtension;

use PHPSpec2\Extension\ExtensionInterface,
    PHPSpec2\Console\ExtendableApplicationInterface as ApplicationInterface,
    PHPSpec2\Configuration\Configuration;

use MageTest\PHPSpec2\MagentoExtension\Loader\SpecificationLoader;

class Extension implements ExtensionInterface
{
    private $application;
    private $configuration;

    public function setApplication(ApplicationInterface $application)
    {
        $this->application = $application;
    }

    public function setConfiguration(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function extend()
    {
        $this->application->extend(
            'specification_loader',
            $this->application->share(function($app){
                return new SpecificationLoader;
            })
        );
    }
}
