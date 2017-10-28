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

use MageTest\PhpSpec\MagentoExtension\Listener\RegisterAutoloaderListener;
use PhpSpec\ObjectBehavior;
use PhpSpec\Process\Context\ExecutionContext;
use PhpSpec\ServiceContainer;
use PhpSpec\Console\ConsoleIO as IO;
use PhpSpec\CodeGenerator\TemplateRenderer;
use PhpSpec\Formatter\Presenter\PresenterInterface;
use PhpSpec\Util\Filesystem;
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
    function let(FakeIndexedServiceContainer $container)
    {
        $container->define(Argument::cetera())->willReturn();

        $container->addConfigurator(Argument::any())->willReturn();

        $container->getParam(Argument::cetera())->willReturn();
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('MageTest\PhpSpec\MagentoExtension\Extension');
    }

    function it_registers_a_console_describe_model_command_when_loaded($container)
    {
        $container->define('console.commands.describe_model', $this->service('\MageTest\PhpSpec\MagentoExtension\Console\Command\DescribeModelCommand', $container), ['console.commands'])->shouldBeCalled();

        $this->load($container);
    }

    function it_registers_a_mage_model_code_generator_when_loaded(
        $container,
        IO $console,
        TemplateRenderer $templateRenderer,
        Filesystem $filesystem,
        ExecutionContext $executionContext
    ) {
        $container->get('console.io')->willReturn($console);
        $container->get('code_generator.templates')->willReturn($templateRenderer);
        $container->get('filesystem')->willReturn($filesystem);
        $container->get('process.executioncontext')->willReturn($executionContext);

        $container->define('code_generator.generators.mage_model', $this->service('\MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\ModelGenerator', $container), ['code_generator.generators'])->shouldBeCalled();

        $this->load($container);
    }
    
    function it_registers_a_console_describe_block_command_when_loaded($container)
    {
        $container->define('console.commands.describe_block', $this->service('\MageTest\PhpSpec\MagentoExtension\Console\Command\DescribeBlockCommand', $container), ['console.commands'])->shouldBeCalled();

        $this->load($container);
    }

    function it_registers_a_mage_block_code_generator_when_loaded(
        $container,
        IO $console,
        TemplateRenderer $templateRenderer,
        Filesystem $filesystem,
        ExecutionContext $executionContext
    ) {
        $container->get('console.io')->willReturn($console);
        $container->get('code_generator.templates')->willReturn($templateRenderer);
        $container->get('filesystem')->willReturn($filesystem);
        $container->get('process.executioncontext')->willReturn($executionContext);

        $container->define('code_generator.generators.mage_block', $this->service('\MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\BlockGenerator', $container), ['code_generator.generators'])->shouldBeCalled();

        $this->load($container);
    }

    function it_registers_a_console_describe_helper_command_when_loaded($container)
    {
        $container->define('console.commands.describe_helper', $this->service('\MageTest\PhpSpec\MagentoExtension\Console\Command\DescribeHelperCommand', $container), ['console.commands'])->shouldBeCalled();

        $this->load($container);
    }

    function it_registers_a_mage_helper_code_generator_when_loaded(
        $container,
        IO $console,
        TemplateRenderer $templateRenderer,
        Filesystem $filesystem,
        ExecutionContext $executionContext
    ) {
        $container->get('console.io')->willReturn($console);
        $container->get('code_generator.templates')->willReturn($templateRenderer);
        $container->get('filesystem')->willReturn($filesystem);
        $container->get('process.executioncontext')->willReturn($executionContext);

        $container->define('code_generator.generators.mage_helper', $this->service('\MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\HelperGenerator', $container), ['code_generator.generators'])->shouldBeCalled();

        $this->load($container);
    }

    function it_registers_a_console_describe_controller_command_when_loaded($container)
    {
        $container->define('console.commands.describe_controller', $this->service('\MageTest\PhpSpec\MagentoExtension\Console\Command\DescribeControllerCommand', $container), ['console.commands'])->shouldBeCalled();

        $this->load($container);
    }

    function it_registers_a_mage_controller_code_generator_when_loaded(
        $container,
        IO $console,
        TemplateRenderer $templateRenderer,
        Filesystem $filesystem,
        ExecutionContext $executionContext
    ) {
        $container->get('console.io')->willReturn($console);
        $container->get('code_generator.templates')->willReturn($templateRenderer);
        $container->get('filesystem')->willReturn($filesystem);
        $container->get('process.executioncontext')->willReturn($executionContext);

        $container->define('code_generator.generators.mage_controller', $this->service('\MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\ControllerGenerator', $container), ['code_generator.generators'])->shouldBeCalled();

        $this->load($container);
    }

    function it_registers_a_mage_controller_specification_generator_when_loaded(
        $container,
        IO $console,
        TemplateRenderer $templateRenderer,
        Filesystem $filesystem,
        ExecutionContext $executionContext
    ) {
        $container->get('console.io')->willReturn($console);
        $container->get('code_generator.templates')->willReturn($templateRenderer);
        $container->get('filesystem')->willReturn($filesystem);
        $container->get('process.executioncontext')->willReturn($executionContext);

        $container->define('code_generator.generators.controller_specification', $this->service('\MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\ControllerSpecificationGenerator', $container), ['code_generator.generators'])->shouldBeCalled();

        $this->load($container);
    }

    function it_adds_event_dispatcher_when_loaded($container)
    {
        $container->define('event_dispatcher.listeners.register_autoloader', $this->service(RegisterAutoloaderListener::class, $container), ['event_dispatcher.listeners'])->shouldBeCalled();

        $this->load($container);
    }

    /**
     * @param string $class
     */
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

class FakeIndexedServiceContainer implements ServiceContainer
{
    public function setParam(string $id, $value)
    {}

    public function getParam(string $id, $default = null)
    {}

    public function set(string $id, $service, array $tags = [])
    {}

    public function define(string $id, callable $definition, array $tags = [])
    {}

    public function get(string $id)
    {}

    public function has(string $id): bool
    {}

    public function remove(string $id)
    {}

    public function getByTag(string $tag): array
    {}

    public function addConfigurator($configurator)
    {}
}
