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


use MageTest\PhpSpec\MagentoExtension\Console\Command\DescribeBlockCommand;
use MageTest\PhpSpec\MagentoExtension\Console\Command\DescribeControllerCommand;
use MageTest\PhpSpec\MagentoExtension\Console\Command\DescribeHelperCommand;
use MageTest\PhpSpec\MagentoExtension\Console\Command\DescribeModelCommand;
use MageTest\PhpSpec\MagentoExtension\Console\Command\DescribeResourceModelCommand;
use PhpSpec\ServiceContainer;

class CommandAssembler implements Assembler
{
    /**
     * @param ServiceContainer $container
     */
    public function build(ServiceContainer $container)
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
} 