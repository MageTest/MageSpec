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

use Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Loader\XmlFileLoader,
    Symfony\Component\Config\FileLocator;

/**
 * ContainerAwareSpecification
 *
 * @category   MageTest
 * @package    PHPSpec2_MagentoExtension
 *
 * @author     MageTest team (https://github.com/MageTest/MageSpec/contributors)
 */
class ContainerAwareSpecification
{
    public function __construct()
    {
        $this->init();
    }

    public function get($service)
    {
        return $this->container->get($service);
    }

    public function init()
    {
        $this->container = new ContainerBuilder;
        $loader = new XmlFileLoader(
            $this->container,
            new FileLocator(
                __DIR__ . DIRECTORY_SEPARATOR . 'Container'
            )
        );

        $loader->load('services.xml');
    }
}