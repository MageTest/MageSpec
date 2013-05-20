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
        $container->setShared('console.commands.describe_model', $this->service('\MageTest\PhpSpec\MagentoExtension\Console\Command\DescribeModelCommand'))->shouldBeCalled();
        $this->load($container);
    }

    function it_adds_locator_configuration_when_loaded($container)
    {
        $container->addConfigurator('locator.locators.mage_locator', true);
        $this->load($container);

    }

    protected function service($class)
    {
        return Argument::that(function ($callback) use ($class) {
            if (is_callable($callback)) {
                $result = $callback();

                return $result instanceof $class;
            }

            return false;
        });
    }
}
