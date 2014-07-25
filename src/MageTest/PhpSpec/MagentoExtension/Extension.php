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

use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\ConfigGenerator;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\Element\BlockElement;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\Element\ControllerElement;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\Element\HelperElement;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\Element\ModelElement;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\Element\ResourceModelElement;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\ModuleGenerator;
use MageTest\PhpSpec\MagentoExtension\Listener\ModuleUpdateListener;
use MageTest\PhpSpec\MagentoExtension\Util\ClassDetector;
use MageTest\PhpSpec\MagentoExtension\Wrapper\VarienWrapperFactory;
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
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\ControllerSpecificationGenerator;
use PhpSpec\Util\Filesystem;
use PrettyXml\Formatter;

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
        $this->setCommands($container);
        $this->setFilesystem($container);
        $this->setFormatter($container);
        $this->setGenerators($container);
        $this->setMaintainers($container);
        $this->setLocators($container);
        $this->setUtils($container);
        $this->setEvents($container);
    }

    private function setCommands(ServiceContainer $container)
    {
        $container->setShared('console.commands.describe_model', function ($c) {
            return new DescribeModelCommand();
        });

        $container->setShared('console.commands.describe_resource_model', function ($c) {
            return new DescribeResourceModelCommand();
        });

        $container->setShared('console.commands.describe_block', function ($c) {
            return new DescribeBlockCommand();
        });

        $container->setShared('console.commands.describe_helper', function ($c) {
            return new DescribeHelperCommand();
        });

        $container->setShared('console.commands.describe_controller', function ($c) {
            return new DescribeControllerCommand();
        });
    }

    private function setFilesystem(ServiceContainer $container)
    {
        $container->setShared('filesystem', function($c) {
            return new Filesystem();
        });
    }

    private function setFormatter(ServiceContainer $container)
    {
        $container->setShared('xml.formatter', function($c) {
            return new Formatter();
        });
    }

    private function setGenerators(ServiceContainer $container)
    {
        $container->setShared('code_generator.generators.mage_model', function ($c) {
            return new ModelGenerator(
                $c->get('console.io'),
                $c->get('code_generator.templates'),
                $c->get('filesystem')
            );
        });

        $container->setShared('code_generator.generators.mage_resource_model', function ($c) {
            return new ResourceModelGenerator(
                $c->get('console.io'),
                $c->get('code_generator.templates'),
                $c->get('filesystem')
            );
        });

        $container->setShared('code_generator.generators.mage_block', function ($c) {
            return new BlockGenerator(
                $c->get('console.io'),
                $c->get('code_generator.templates'),
                $c->get('filesystem')
            );
        });

        $container->setShared('code_generator.generators.mage_helper', function ($c) {
            return new HelperGenerator(
                $c->get('console.io'),
                $c->get('code_generator.templates'),
                $c->get('filesystem')
            );
        });

        $container->setShared('code_generator.generators.mage_controller', function($c) {
            return new ControllerGenerator(
                $c->get('console.io'),
                $c->get('code_generator.templates'),
                $c->get('filesystem')
            );
        });

        $container->setShared('code_generator.generators.controller_specification', function($c) {
            return new ControllerSpecificationGenerator(
                $c->get('console.io'),
                $c->get('code_generator.templates'),
                $c->get('filesystem')
            );
        });

        $container->setShared('xml_generator.generators.module', function($c) {
            $suite = $c->getParam('mage_locator', array('main' => ''));
            if (isset($suite['src_path'])) {
                $etcPath = rtrim($suite['src_path'], '/') . DIRECTORY_SEPARATOR . '..'
                    . DIRECTORY_SEPARATOR . 'etc' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR;
            } else {
                $etcPath = 'app/etc/';
            }
            $codePool = isset($suite['code_pool']) ? $suite['code_pool'] : 'local';
            return new ModuleGenerator(
                $etcPath,
                $c->get('filesystem'),
                $codePool
            );
        });

        $container->setShared('xml_generator.generators.config', function($c) {
            $suite = $c->getParam('mage_locator', array('main' => ''));
            $srcPath = isset($suite['src_path']) ? rtrim($suite['src_path'], '/') . DIRECTORY_SEPARATOR : 'src';
            $codePool = isset($suite['code_pool']) ? $suite['code_pool'] : 'local';
            $generator = new ConfigGenerator(
                $srcPath,
                $c->get('filesystem'),
                $c->get('xml.formatter'),
                $codePool
            );

            array_map(
                array($generator, 'addElementGenerator'),
                $c->getByPrefix('xml_generator.generators.config.element')
            );

            return $generator;
        });

        $container->setShared('xml_generator.generators.config.element.block', function($c) {
           return new BlockElement();
        });

        $container->setShared('xml_generator.generators.config.element.helper', function($c) {
            return new HelperElement();
        });

        $container->setShared('xml_generator.generators.config.element.controller', function($c) {
            return new ControllerElement();
        });

        $container->setShared('xml_generator.generators.config.element.model', function($c) {
            return new ModelElement();
        });

        $container->setShared('xml_generator.generators.config.element.resource_model', function($c) {
            return new ResourceModelElement();
        });
    }

    private function setMaintainers(ServiceContainer $container)
    {
        $container->setShared('runner.maintainers.varien_subject', function ($c) {
            return new VarienSubjectMaintainer(
                $c->get('formatter.presenter'),
                $c->get('event_dispatcher'),
                new VarienWrapperFactory()
            );
        });
    }

    private function setLocators(ServiceContainer $container)
    {
        $extension = $this;
        $container->addConfigurator(function ($c) use ($extension) {
            $suite = $c->getParam('mage_locator', array('main' => ''));

            $srcNS = isset($suite['namespace']) ? $suite['namespace'] : '';
            $specPrefix = isset($suite['spec_prefix']) ? $suite['spec_prefix'] : '';
            $srcPath = isset($suite['src_path']) ? rtrim($suite['src_path'], '/') . DIRECTORY_SEPARATOR : 'src';
            $specPath = isset($suite['spec_path']) ? rtrim($suite['spec_path'], '/') . DIRECTORY_SEPARATOR : '.';
            $codePool = isset($suite['code_pool']) ? $suite['code_pool'] : 'local';
            $filesystem = null;

            if (!is_dir($srcPath)) {
                mkdir($srcPath, 0777, true);
            }
            if (!is_dir($specPath)) {
                mkdir($specPath, 0777, true);
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

            $extension->configureAutoloader($srcPath, $codePool);
        });
    }

    private function setUtils(ServiceContainer $container)
    {
        $container->setShared('util.class_detector', function ($c) {
           return new ClassDetector();
        });
    }

    private function setEvents(ServiceContainer $container)
    {
        $container->setShared('event_dispatcher.listeners.module_update', function ($c) {
            return new ModuleUpdateListener(
                $c->get('xml_generator.generators.module'),
                $c->get('xml_generator.generators.config'),
                $c->get('console.io'),
                $c->get('util.class_detector')
            );
        });
    }

    public function configureAutoloader($srcPath, $codePool)
    {
        MageLoader::register($srcPath, $codePool);
    }
}
