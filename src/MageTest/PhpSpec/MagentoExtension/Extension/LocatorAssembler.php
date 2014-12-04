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

use MageTest\PhpSpec\MagentoExtension\Locator\Magento\BlockLocator;
use MageTest\PhpSpec\MagentoExtension\Locator\Magento\ControllerLocator;
use MageTest\PhpSpec\MagentoExtension\Locator\Magento\HelperLocator;
use MageTest\PhpSpec\MagentoExtension\Locator\Magento\ModelLocator;
use MageTest\PhpSpec\MagentoExtension\Locator\Magento\ResourceModelLocator;
use PhpSpec\ServiceContainer;

class LocatorAssembler implements Assembler
{

    /**
     * @param ServiceContainer $container
     */
    public function build(ServiceContainer $container)
    {
        $assembler = $this;
        $container->addConfigurator(function ($c) use ($assembler) {
            $config = $c->getParam('mage_locator', array('main' => ''));

            $srcNS = $assembler->getNamespace($config);
            $specPrefix = $assembler->getSpecPrefix($config);
            $srcPath = $assembler->getSrcPath($config);
            $specPath = $assembler->getSpecPath($config);
            $codePool = $assembler->getCodePool($config);
            $filesystem = $c->get('filesystem');

            if (!$filesystem->isDirectory($srcPath)) {
                $filesystem->makeDirectory($srcPath);
            }

            if (!$filesystem->isDirectory($specPath)) {
                $filesystem->makeDirectory($specPath);
            }

            $c->setShared('locator.locators.model_locator',
                function ($c) use ($srcNS, $specPrefix, $srcPath, $specPath, $filesystem, $codePool) {
                    return new ModelLocator($srcNS, $specPrefix, $srcPath, $specPath, $filesystem, $codePool);
                }
            );

            $c->setShared('locator.locators.resource_model_locator',
                function ($c) use ($srcNS, $specPrefix, $srcPath, $specPath, $filesystem, $codePool) {
                    return new ResourceModelLocator($srcNS, $specPrefix, $srcPath, $specPath, $filesystem, $codePool);
                }
            );

            $c->setShared('locator.locators.block_locator',
                function ($c) use ($srcNS, $specPrefix, $srcPath, $specPath, $filesystem, $codePool) {
                    return new BlockLocator($srcNS, $specPrefix, $srcPath, $specPath, $filesystem, $codePool);
                }
            );

            $c->setShared('locator.locators.helper_locator',
                function ($c) use ($srcNS, $specPrefix, $srcPath, $specPath, $filesystem, $codePool) {
                    return new HelperLocator($srcNS, $specPrefix, $srcPath, $specPath, $filesystem, $codePool);
                }
            );

            $c->setShared('locator.locators.controller_locator',
                function ($c) use ($srcNS, $specPrefix, $srcPath, $specPath, $filesystem, $codePool) {
                    return new ControllerLocator($srcNS, $specPrefix, $srcPath, $specPath, $filesystem, $codePool);
                }
            );
        });
    }

    public function getNamespace(array $config)
    {
        return array_key_exists('namespace', $config) ? $config['namespace'] : '';
    }

    public function getSpecPrefix(array $config)
    {
        return array_key_exists('spec_prefix', $config) ? $config['spec_prefix'] : '';
    }

    public function getSrcPath(Array $config)
    {
        return array_key_exists('src_path', $config) ? rtrim($config['src_path'], '/') . DIRECTORY_SEPARATOR : 'src';
    }

    public function getSpecPath(array $config)
    {
        return array_key_exists('spec_path', $config) ? rtrim($config['spec_path'], '/') . DIRECTORY_SEPARATOR : '.';
    }

    public function getCodePool(array $config)
    {
        return array_key_exists('code_pool', $config) ? $config['code_pool'] : 'local';
    }
}