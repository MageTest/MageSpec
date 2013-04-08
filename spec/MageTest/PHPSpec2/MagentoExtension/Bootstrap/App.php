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
 * @subpackage Bootstrap
 *
 * @copyright  Copyright (c) 2012-2013 MageTest team and contributors.
 */
namespace spec\MageTest\PHPSpec2\MagentoExtension\Bootstrap;

use PHPSpec2\ObjectBehavior;

/**
 * App
 *
 * @category   MageTest
 * @package    PHPSpec2_MagentoExtension
 * @subpackage Bootstrap
 *
 * @author     MageTest team (https://github.com/MageTest/MageSpec/contributors)
 */
class App extends ObjectBehavior
{
    function let()
    {
        $app->beAMockOf('MageTest\PHPSpec2\MagentoExtension\Bootstrap\App');
        $this->beConstructedWith($app);
    }
}