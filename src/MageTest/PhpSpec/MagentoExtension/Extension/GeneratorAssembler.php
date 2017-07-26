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

use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\BlockGenerator;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\ControllerGenerator;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\ControllerSpecificationGenerator;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\HelperGenerator;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\ModelGenerator;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\ConfigGenerator;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\Element\BlockElement;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\Element\ControllerElement;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\Element\HelperElement;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\Element\ModelElement;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\ModuleGenerator;
use MageTest\PhpSpec\MagentoExtension\Configuration\MageLocator;
use PhpSpec\Process\Context\JsonExecutionContext;
use PhpSpec\ServiceContainer;

class GeneratorAssembler implements Assembler
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
        $this->setCodeGenerators($container);
        $this->setXmlModuleGenerator($container);
        $this->setXmlConfigGenerator($container);
        $this->setXmlElementGenerators($container);
    }

    /**
     * @param ServiceContainer $container
     */
    private function setCodeGenerators(ServiceContainer $container)
    {
        $container->define('code_generator.generators.mage_model', function ($c) {
            return new ModelGenerator(
                $c->get('console.io'),
                $c->get('code_generator.templates'),
                $c->get('filesystem'),
                new JsonExecutionContext()
            );
        }, ['code_generator.generators']);

        $container->define('code_generator.generators.mage_block', function ($c) {
            return new BlockGenerator(
                $c->get('console.io'),
                $c->get('code_generator.templates'),
                $c->get('filesystem'),
                new JsonExecutionContext()
            );
        }, ['code_generator.generators']);

        $container->define('code_generator.generators.mage_helper', function ($c) {
            return new HelperGenerator(
                $c->get('console.io'),
                $c->get('code_generator.templates'),
                $c->get('filesystem'),
                new JsonExecutionContext()
            );
        }, ['code_generator.generators']);

        $container->define('code_generator.generators.mage_controller', function ($c) {
            return new ControllerGenerator(
                $c->get('console.io'),
                $c->get('code_generator.templates'),
                $c->get('filesystem'),
                new JsonExecutionContext()
            );
        }, ['code_generator.generators']);

        $container->define('code_generator.generators.controller_specification', function ($c) {
            return new ControllerSpecificationGenerator(
                $c->get('console.io'),
                $c->get('code_generator.templates'),
                $c->get('filesystem'),
                new JsonExecutionContext()
            );
        }, ['code_generator.generators']);
    }

    /**
     * @param ServiceContainer $container
     */
    private function setXmlModuleGenerator(ServiceContainer $container)
    {
        $container->define('xml_generator.generators.module', function ($c) {
            $srcPath = $this->configuration->getSrcPath();
            if ($srcPath === MageLocator::DEFAULT_SRC_PATH) {
                $etcPath = 'app/etc/';
            } else {
                $etcPath = $srcPath . DIRECTORY_SEPARATOR . '..'
                    . DIRECTORY_SEPARATOR . 'etc' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR;
            }
            return new ModuleGenerator(
                $etcPath,
                $c->get('filesystem'),
                $this->configuration->getCodePool()
            );
        }, ['xml_generator.generators']);
    }

    /**
     * @param ServiceContainer $container
     */
    private function setXmlConfigGenerator(ServiceContainer $container)
    {
        $container->define('xml_generator.generators.config', function ($c) {
            $generator = new ConfigGenerator(
                $this->configuration->getSrcPath(),
                $c->get('filesystem'),
                $c->get('xml.formatter'),
                $this->configuration->getCodePool()
            );

            array_map(
                [$generator, 'addElementGenerator'],
                $c->getByTag('xml_generator.generators.config.element')
            );

            return $generator;
        }, ['xml_generator.generators']);
    }

    /**
     * @param ServiceContainer $container
     */
    private function setXmlElementGenerators(ServiceContainer $container)
    {
        $container->define('xml_generator.generators.config.element.block', function () {
            return new BlockElement();
        }, ['xml_generator.generators.config.element']);

        $container->define('xml_generator.generators.config.element.helper', function () {
            return new HelperElement();
        }, ['xml_generator.generators.config.element']);

        $container->define('xml_generator.generators.config.element.controller', function () {
            return new ControllerElement();
        }, ['xml_generator.generators.config.element']);

        $container->define('xml_generator.generators.config.element.model', function () {
            return new ModelElement();
        }, ['xml_generator.generators.config.element']);
    }
}
