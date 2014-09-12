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
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\ResourceModelGenerator;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\ConfigGenerator;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\Element\BlockElement;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\Element\ControllerElement;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\Element\HelperElement;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\Element\ModelElement;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\Element\ResourceModelElement;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\ModuleGenerator;
use PhpSpec\ServiceContainer;

class GeneratorAssembler implements Assembler
{
    /**
     * @param ServiceContainer $container
     */
    public function build(ServiceContainer $container)
    {
        $this->setCodeGenerators($container);
        $this->setXmlGenerators($container);
        $this->setXmlElementGenerators($container);
    }

    private function setCodeGenerators(ServiceContainer $container)
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
    }

    private function setXmlGenerators(ServiceContainer $container)
    {
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
    }

    private function setXmlElementGenerators(ServiceContainer $container)
    {
        $container->setShared('xml_generator.generators.config.element.block', function() {
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
}