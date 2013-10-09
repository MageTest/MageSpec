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
namespace spec\MageTest\PhpSpec\MagentoExtension;

use PhpSpec\ObjectBehavior;
use PhpSpec\ServiceContainer;
use PhpSpec\Console\IO;
use PhpSpec\CodeGenerator\TemplateRenderer;
use PhpSpec\Formatter\Presenter\PresenterInterface;
use PhpSpec\Wrapper\Unwrapper;
use Prophecy\Argument;

/**
 * ExtensionSpec
 *
 * @category   MageTest
 * @package    PhpSpec_MagentoExtension
 *
 * @author     MageTest team (https://github.com/MageTest/MageSpec/contributors)
 */
class ExtensionSpec extends ObjectBehavior
{
    function let(ServiceContainer $container)
    {
        $container->setShared(Argument::cetera())->willReturn();

        $container->addConfigurator(Argument::any())->willReturn();
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('MageTest\PhpSpec\MagentoExtension\Extension');
    }

    function it_registers_a_console_describe_model_command_when_loaded($container)
    {
        $container->setShared('console.commands.describe_model', $this->service('\MageTest\PhpSpec\MagentoExtension\Console\Command\DescribeModelCommand', $container))->shouldBeCalled();

        $this->load($container);
    }

    function it_registers_a_mage_model_code_generator_when_loaded($container, IO $console, TemplateRenderer $templateRenderer)
    {
        $container->get('console.io')->willReturn($console);
        $container->get('code_generator.templates')->willReturn($templateRenderer);

        $container->setShared('code_generator.generators.mage_model', $this->service('\MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\ModelGenerator', $container))->shouldBeCalled();

        $this->load($container);
    }

    function it_registers_a_console_describe_resource_model_command_when_loaded($container)
    {
        $container->setShared('console.commands.describe_resource_model', $this->service('\MageTest\PhpSpec\MagentoExtension\Console\Command\DescribeResourceModelCommand', $container))->shouldBeCalled();

        $this->load($container);
    }

    function it_registers_a_mage_resource_model_code_generator_when_loaded($container, IO $console, TemplateRenderer $templateRenderer)
    {
        $container->get('console.io')->willReturn($console);
        $container->get('code_generator.templates')->willReturn($templateRenderer);

        $container->setShared('code_generator.generators.mage_resource_model', $this->service('\MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\ResourceModelGenerator', $container))->shouldBeCalled();

        $this->load($container);
    }

    function it_registers_a_console_describe_block_command_when_loaded($container)
    {
        $container->setShared('console.commands.describe_block', $this->service('\MageTest\PhpSpec\MagentoExtension\Console\Command\DescribeBlockCommand', $container))->shouldBeCalled();

        $this->load($container);
    }

    function it_registers_a_mage_block_code_generator_when_loaded($container, IO $console, TemplateRenderer $templateRenderer)
    {
        $container->get('console.io')->willReturn($console);
        $container->get('code_generator.templates')->willReturn($templateRenderer);

        $container->setShared('code_generator.generators.mage_block', $this->service('\MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\BlockGenerator', $container))->shouldBeCalled();

        $this->load($container);
    }

    function it_registers_a_console_describe_helper_command_when_loaded($container)
    {
        $container->setShared('console.commands.describe_helper', $this->service('\MageTest\PhpSpec\MagentoExtension\Console\Command\DescribeHelperCommand', $container))->shouldBeCalled();

        $this->load($container);
    }

    function it_registers_a_mage_helper_code_generator_when_loaded($container, IO $console, TemplateRenderer $templateRenderer)
    {
        $container->get('console.io')->willReturn($console);
        $container->get('code_generator.templates')->willReturn($templateRenderer);

        $container->setShared('code_generator.generators.mage_helper', $this->service('\MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\HelperGenerator', $container))->shouldBeCalled();

        $this->load($container);
    }

    function it_registers_a_console_describe_controller_command_when_loaded($container)
    {
        $container->setShared('console.commands.describe_controller', $this->service('\MageTest\PhpSpec\MagentoExtension\Console\Command\DescribeControllerCommand', $container))->shouldBeCalled();

        $this->load($container);
    }

    function it_registers_a_mage_controller_code_generator_when_loaded($container, IO $console, TemplateRenderer $templateRenderer)
    {
        $container->get('console.io')->willReturn($console);
        $container->get('code_generator.templates')->willReturn($templateRenderer);

        $container->setShared('code_generator.generators.mage_controller', $this->service('\MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\ControllerGenerator', $container))->shouldBeCalled();

        $this->load($container);
    }

    function it_registers_a_mage_controller_specification_generator_when_loaded($container, IO $console, TemplateRenderer $templateRenderer)
    {
        $container->get('console.io')->willReturn($console);
        $container->get('code_generator.templates')->willReturn($templateRenderer);

        $container->setShared('code_generator.generators.controller_specification', $this->service('\MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\ControllerSpecificationGenerator', $container))->shouldBeCalled();

        $this->load($container);
    }

    function it_registers_a_varien_subject_maintainer_when_loaded($container, PresenterInterface $presenter, Unwrapper $unwrapper)
    {
        $container->get('formatter.presenter')->willReturn($presenter);
        $container->get('unwrapper')->willReturn($unwrapper);

        $container->setShared('runner.maintainers.varien_subject', $this->service('\MageTest\PhpSpec\MagentoExtension\Runner\Maintainer\VarienSubjectMaintainer', $container))->shouldBeCalled();

        $this->load($container);
    }

    function it_adds_locator_configuration_when_loaded($container)
    {
        $container->addConfigurator('locator.locators.mage_locator', true);

        $this->load($container);
    }

    protected function service($class, $container)
    {
        return Argument::that(function ($callback) use ($class, $container) {
            if (is_callable($callback)) {
                $result = $callback($container->getWrappedObject());

                return $result instanceof $class;
            }

            return false;
        });
    }
}
