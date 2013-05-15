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

use PhpSpec\Extension\ExtensionInterface,
    PhpSpec\Console\ExtendableApplicationInterface as ApplicationInterface,
    PhpSpec\Configuration\Configuration,
    PhpSpec\ServiceContainer,
    PhpSpec\Locator\PSR0\PSR0Locator;

use MageTest\PhpSpec\MagentoExtension\Console\Command\DescribeModelCommand,
    MageTest\PhpSpec\MagentoExtension\Locator\Magento\ModelLocator;
//    MageTest\PhpSpec\MagentoExtension\Loader\SpecificationsClassLoader;

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

    }
}
