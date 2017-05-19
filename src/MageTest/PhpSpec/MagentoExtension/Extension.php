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
use PhpSpec\Extension as PhpspecExtension;
use PhpSpec\ServiceContainer;
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
class Extension implements PhpspecExtension
{
    public function load(ServiceContainer $container, array $params = [])
    {
        $this->setCommands($container);
        $this->setFilesystem($container);
        $this->setFormatter($container);
        $this->setGenerators($container, $params);
        $this->setAccessInspector($container);
        $this->setLocators($container, $params);
        $this->setUtils($container);
        $this->setEvents($container, $params);
    }

    private function setCommands(ServiceContainer $container)
    {
        $commandAssembler = new CommandAssembler();
        $commandAssembler->build($container);
    }

    private function setFilesystem(ServiceContainer $container)
    {
        $container->define('filesystem', function() {
            return new Filesystem();
        }, ['filesystem']);
    }

    private function setFormatter(ServiceContainer $container)
    {
        $container->define('xml.formatter', function() {
            return new Formatter();
        }, ['xml.formatter']);
    }

    private function setGenerators(ServiceContainer $container, $params)
    {
        $generatorAssembler = new GeneratorAssembler($params);
        $generatorAssembler->build($container);
    }

    private function setAccessInspector(ServiceContainer $container)
    {
        $container->define('access_inspector', function($c) {
            return $c->get('access_inspector.visibility');
        }, ['access_inspector']);
    }

    private function setLocators(ServiceContainer $container, array $params)
    {
        $locatorAssembler = new LocatorAssembler($params);
        $locatorAssembler->build($container);
    }

    private function setUtils(ServiceContainer $container)
    {
        $container->define('util.class_detector', function () {
            return new ClassDetector();
        }, ['util.class_detector']);
    }

    private function setEvents(ServiceContainer $container, array $params = [])
    {
        $container->define('event_dispatcher.listeners.bootstrap', function ($c) use ($params) {
            return new Listener\BootstrapListener(
                $params,
                $c->getParam('mage_locator',  array('main' => ''))
            );
        }, ['event_dispatcher.listeners']);

        $container->define('event_dispatcher.listeners.module_update', function ($c) {
            return new ModuleUpdateListener(
                $c->get('xml_generator.generators.module'),
                $c->get('xml_generator.generators.config'),
                $c->get('console.io'),
                $c->get('util.class_detector')
            );
        }, ['event_dispatcher.listeners']);
    }
}
