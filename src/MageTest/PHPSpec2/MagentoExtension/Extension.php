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
 * @package    PHPSpec2_MagentoExtension
 *
 * @copyright  Copyright (c) 2012-2013 MageTest team and contributors.
 */
namespace MageTest\PHPSpec2\MagentoExtension;

use PHPSpec2\Extension\ExtensionInterface,
    PHPSpec2\Console\ExtendableApplicationInterface as ApplicationInterface,
    PHPSpec2\Configuration\Configuration;

use MageTest\PHPSpec2\MagentoExtension\Loader\SpecificationsClassLoader;
use PHPSpec2\ServiceContainer;

/**
 * Extension
 *
 * @category   MageTest
 * @package    PHPSpec2_MagentoExtension
 *
 * @author     MageTest team (https://github.com/MageTest/MageSpec/contributors)
 */
class Extension implements ExtensionInterface
{
    private $application;

    public function initialize(ServiceContainer $container)
    {
        $container->set('specifications_loader', $container->share(function($c) {
            return new SpecificationsClassLoader;
        }));
    }
}
