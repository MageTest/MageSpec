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

use PhpSpec\Extension\ExtensionInterface;
use PhpSpec\Console\ExtendableApplicationInterface as ApplicationInterface;
use PhpSpec\ServiceContainer;

use MageTest\PhpSpec\MagentoExtension\Autoloader\MageLoader;

use MageTest\PhpSpec\MagentoExtension\Runner\Maintainer\VarienSubjectMaintainer;

use MageTest\PhpSpec\MagentoExtension\Console\Command\DescribeModelCommand;
use MageTest\PhpSpec\MagentoExtension\Locator\Magento\ModelLocator;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\ModelGenerator;

use MageTest\PhpSpec\MagentoExtension\Console\Command\DescribeResourceModelCommand;
use MageTest\PhpSpec\MagentoExtension\Locator\Magento\ResourceModelLocator;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\ResourceModelGenerator;

use MageTest\PhpSpec\MagentoExtension\Console\Command\DescribeBlockCommand;
use MageTest\PhpSpec\MagentoExtension\Locator\Magento\BlockLocator;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\BlockGenerator;

use MageTest\PhpSpec\MagentoExtension\Console\Command\DescribeHelperCommand;
use MageTest\PhpSpec\MagentoExtension\Locator\Magento\HelperLocator;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\HelperGenerator;

use MageTest\PhpSpec\MagentoExtension\Console\Command\DescribeControllerCommand;
use MageTest\PhpSpec\MagentoExtension\Locator\Magento\ControllerLocator;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\ControllerGenerator;

use MageTest\PhpSpec\MagentoExtension\Locator\Magento\ModuleInitXmlLocator;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\ModuleInitXmlGenerator;

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
        $container->setShared('console.commands.describe_model', function ($c) {
            return new DescribeModelCommand();
        });

        $container->setShared('code_generator.generators.mage_model', function($c) {
            return new ModelGenerator(
                $c->get('console.io'),
                $c->get('code_generator.templates')
            );
        });

        $container->setShared('console.commands.describe_resource_model', function ($c) {
            return new DescribeResourceModelCommand();
        });

        $container->setShared('code_generator.generators.mage_resource_model', function($c) {
            return new ResourceModelGenerator(
                $c->get('console.io'),
                $c->get('code_generator.templates')
            );
        });

        $container->setShared('console.commands.describe_block', function ($c) {
            return new DescribeBlockCommand();
        });

        $container->setShared('code_generator.generators.mage_block', function($c) {
            return new BlockGenerator(
                $c->get('console.io'),
                $c->get('code_generator.templates')
            );
        });

        $container->setShared('console.commands.describe_helper', function ($c) {
            return new DescribeHelperCommand();
        });

        $container->setShared('code_generator.generators.mage_helper', function($c) {
            return new HelperGenerator(
                $c->get('console.io'),
                $c->get('code_generator.templates')
            );
        });

        $container->setShared('console.commands.describe_controller', function ($c) {
            return new DescribeControllerCommand();
        });

        $container->setShared('code_generator.generators.mage_controller', function($c) {
            return new ControllerGenerator(
                $c->get('console.io'),
                $c->get('code_generator.templates')
            );
        });

        $container->setShared('code_generator.generators.module_init_xml', function($c) {
            return new ModuleInitXmlGenerator(
                $c->get('console.io'),
                $c->get('code_generator.templates')
            );
        });

        $container->setShared('runner.maintainers.varien_subject', function($c) {
            return new VarienSubjectMaintainer(
                $c->get('formatter.presenter'),
                $c->get('unwrapper')
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

            $c->setShared('locator.locators.resource_model_locator',
                function($c) use($srcNS, $specPrefix, $srcPath, $specPath) {
                    return new ResourceModelLocator($srcNS, $specPrefix, $srcPath, $specPath);
                }
            );

            $c->setShared('locator.locators.block_locator',
                function($c) use($srcNS, $specPrefix, $srcPath, $specPath) {
                    return new BlockLocator($srcNS, $specPrefix, $srcPath, $specPath);
                }
            );

            $c->setShared('locator.locators.helper_locator',
                function($c) use($srcNS, $specPrefix, $srcPath, $specPath) {
                    return new HelperLocator($srcNS, $specPrefix, $srcPath, $specPath);
                }
            );

            $c->setShared('locator.locators.controller_locator',
                function($c) use($srcNS, $specPrefix, $srcPath, $specPath) {
                    return new ControllerLocator($srcNS, $specPrefix, $srcPath, $specPath);
                }
            );

            $c->setShared('locator.locators.module_init_xml_locator',
                function($c) use($srcNS, $specPrefix, $srcPath, $specPath) {
                    return new ModuleInitXmlLocator($srcNS, $specPrefix, $srcPath, $specPath);
                }
            );
        });

        $this->bootstrap();
    }

    public function bootstrap()
    {
        \Mage::app();

        $autoloader_callbacks = spl_autoload_functions();

        $original_autoload=null;
        foreach($autoloader_callbacks as &$callback)
        {
            if(is_array($callback) && ($callback[0] instanceof \Varien_Autoload))
            {
                spl_autoload_unregister($callback);
            }
        }

        MageLoader::register();
    }
}
