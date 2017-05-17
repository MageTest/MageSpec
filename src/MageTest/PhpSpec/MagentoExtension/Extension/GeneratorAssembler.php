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
use PhpSpec\Process\Context\JsonExecutionContext;
use PhpSpec\ServiceContainer;

class GeneratorAssembler implements Assembler
{
    /**
     * @var array
     */
    private $params;

    public function __construct(array $params = [])
    {
        $this->params = $params;
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

        $container->define('code_generator.generators.mage_controller', function($c) {
            return new ControllerGenerator(
                $c->get('console.io'),
                $c->get('code_generator.templates'),
                $c->get('filesystem'),
                new JsonExecutionContext()
            );
        }, ['code_generator.generators']);

        $container->define('code_generator.generators.controller_specification', function($c) {
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
        $params = $this->params;
        $container->define('xml_generator.generators.module', function ($c) use ($params) {
            $suite = $config = isset($params['mage_locator']) ? $params['mage_locator'] : $c->getParam('mage_locator',  array('main' => ''));
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
        }, ['xml_generator.generators']);
    }

    /**
     * @param ServiceContainer $container
     */
    private function setXmlConfigGenerator(ServiceContainer $container)
    {
        $params = $this->params;
        $container->define('xml_generator.generators.config', function($c) use ($params) {
            $suite = $config = isset($params['mage_locator']) ? $params['mage_locator'] : $c->getParam('mage_locator',  array('main' => ''));
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
        $container->define('xml_generator.generators.config.element.block', function() {
            return new BlockElement();
        }, ['xml_generator.generators.config.element']);

        $container->define('xml_generator.generators.config.element.helper', function() {
            return new HelperElement();
        }, ['xml_generator.generators.config.element']);

        $container->define('xml_generator.generators.config.element.controller', function() {
            return new ControllerElement();
        }, ['xml_generator.generators.config.element']);

        $container->define('xml_generator.generators.config.element.model', function() {
            return new ModelElement();
        }, ['xml_generator.generators.config.element']);
    }
}
