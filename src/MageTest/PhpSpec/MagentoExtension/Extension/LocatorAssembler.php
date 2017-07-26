<?php
/**
 * MageSpec
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License, that is bundled with this
 * package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 *
 * http://opensource.org/licenses/MIT
 *
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world-wide-web, please send an email
 * to <magetest@sessiondigital.com> so we can send you a copy immediately.
 *
 * @category   MageTest
 * @package    PhpSpec_MagentoExtension
 *
 * @copyright  Copyright (c) 2012-2013 MageTest team and contributors.
 */
namespace MageTest\PhpSpec\MagentoExtension\Extension;

use MageTest\PhpSpec\MagentoExtension\Configuration\MageLocator;
use PhpSpec\ServiceContainer;

class LocatorAssembler implements Assembler
{
    private $configuration;

    public function __construct(MageLocator $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @param ServiceContainer $container
     */
    public function build(ServiceContainer $container)
    {
        $container->addConfigurator(function ($c) {

            $srcNS = $this->configuration->getNamespace();
            $specPrefix = $this->configuration->getSpecPrefix();
            $srcPath = $this->configuration->getSrcPath();
            $specPath = $this->configuration->getSpecPath();
            $codePool = $this->configuration->getCodePool();
            $filesystem = $c->get('filesystem');

            if (!$filesystem->isDirectory($srcPath)) {
                $filesystem->makeDirectory($srcPath);
            }

            if (!$filesystem->isDirectory($specPath)) {
                $filesystem->makeDirectory($specPath);
            }

            $factory = new LocatorFactory($srcNS, $specPrefix, $srcPath, $specPath, $filesystem, $codePool);

            $c->define('locator.locators.magento.model_locator',
                function () use ($factory) {
                    return $factory->getLocator('model');
                },
                ['locator.locators.magento']
            );

            $c->define('locator.locators.magento.block_locator',
                function () use ($factory) {
                    return $factory->getLocator('block');
                },
                ['locator.locators.magento']
            );

            $c->define('locator.locators.magento.helper_locator',
                function () use ($factory) {
                    return $factory->getLocator('helper');
                },
                ['locator.locators.magento']
            );

            $c->define('locator.locators.magento.controller_locator',
                function () use ($factory) {
                    return $factory->getLocator('controller');
                },
                ['locator.locators.magento']
            );

            $resourceManager = $c->get('locator.resource_manager');

            array_map(
                array($resourceManager, 'registerLocator'),
                $c->getByTag('locator.locators.magento')
            );
        });
    }
}
