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
 * @subpackage Bootstrap
 *
 * @copyright  Copyright (c) 2012-2013 MageTest team and contributors.
 */
namespace spec\MageTest\PhpSpec\MagentoExtension\Bootstrap;

use PhpSpec\ObjectBehavior;

/**
 * AppSpec
 *
 * @category   MageTest
 * @package    PhpSpec_MagentoExtension
 * @subpackage Bootstrap
 *
 * @author     MageTest team (https://github.com/MageTest/MageSpec/contributors)
 */
class AppSpec extends ObjectBehavior
{
    function let()
    {
        $app->beAMockOf('MageTest\PhpSpec\MagentoExtension\Bootstrap\App');
        $this->beConstructedWith($app);
    }
}