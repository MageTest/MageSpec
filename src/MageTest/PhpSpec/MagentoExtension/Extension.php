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
namespace MageTest\PhpSpec\MagentoExtension;

use PhpSpec\Extension\ExtensionInterface,
    PhpSpec\Console\ExtendableApplicationInterface as ApplicationInterface,
    PhpSpec\Configuration\Configuration,
    PhpSpec\ServiceContainer,
    PhpSpec\Locator\PSR0\PSR0Locator;

use MageTest\PhpSpec\MagentoExtension\Console\Command\DescribeModelCommand,
    MageTest\PhpSpec\MagentoExtension\Locator\Magento\ModelLocator,
    MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\ModelGenerator;
//    MageTest\PhpSpec\MagentoExtension\Loader\SpecificationsClassLoader;

/**
 * Extension
 *
 * @category   MageTest
 * @package    PhpSpec_MagentoExtension
 *
 * @author     MageTest team (https://github.com/MageTest/MageSpec/contributors)
 */
class Extension implements ExtensionInterface
{
    public function load(ServiceContainer $container)
    {
        $container->setShared('console.commands.describe_model', function () {
            return new DescribeModelCommand();
        });

        $container->setShared('code_generator.generators.mage_model', function($c) {
            return new ModelGenerator(
                $c->get('console.io'),
                $c->get('code_generator.templates')
            );
        });

        $container->addConfigurator(function($c) {
            $suite = $c->getParam('mage_locator', array('main' => ''));

            $srcNS      = isset($suite['namespace']) ? $suite['namespace'] : '';
            $specPrefix = isset($suite['spec_prefix']) ? $suite['spec_prefix'] : '';
            $srcPath    = isset($suite['src_path']) ? rtrim($suite['src_path'], '/') . DIRECTORY_SEPARATOR : 'src';
            $specPath   = isset($suite['spec_path']) ? rtrim($suite['spec_path'], '/') . DIRECTORY_SEPARATOR : '.';

            if (!is_dir($srcPath)) {
                mkdir($srcPath, 0777, true);
            }
            if (!is_dir($specPath)) {
                mkdir($specPath, 0777, true);
            }

            $c->setShared('locator.locators.model_locator',
                function($c) use($srcNS, $specPrefix, $srcPath, $specPath) {
                    return new ModelLocator($srcNS, $specPrefix, $srcPath, $specPath);
                }
            );
        });
    }
}
