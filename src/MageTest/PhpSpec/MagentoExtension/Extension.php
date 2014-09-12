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

use MageTest\PhpSpec\MagentoExtension\Extension\CommandAssembler;
use MageTest\PhpSpec\MagentoExtension\Extension\GeneratorAssembler;
use MageTest\PhpSpec\MagentoExtension\Extension\LocatorAssembler;
use MageTest\PhpSpec\MagentoExtension\Listener\ModuleUpdateListener;
use MageTest\PhpSpec\MagentoExtension\Util\ClassDetector;
use MageTest\PhpSpec\MagentoExtension\Wrapper\VarienWrapperFactory;
use PhpSpec\Extension\ExtensionInterface;
use PhpSpec\ServiceContainer;
use MageTest\PhpSpec\MagentoExtension\Autoloader\MageLoader;
use MageTest\PhpSpec\MagentoExtension\Runner\Maintainer\VarienSubjectMaintainer;
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
        $this->configureAutoloader($container);
    }

    private function setCommands(ServiceContainer $container)
    {
        $commandAssembler = new CommandAssembler();
        $commandAssembler->build($container);
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
        $generatorAssembler = new GeneratorAssembler();
        $generatorAssembler->build($container);
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
        $locatorAssembler = new LocatorAssembler();
        $locatorAssembler->build($container);
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

    private function configureAutoloader($container)
    {
        $container->addConfigurator(function ($c) {
            $suite = $c->getParam('mage_locator', array('main' => ''));
            MageLoader::register(
                isset($suite['src_path']) ? rtrim($suite['src_path'], '/') . DIRECTORY_SEPARATOR : 'src',
                isset($suite['code_pool']) ? $suite['code_pool'] : 'local'
            );
        });
    }
}
