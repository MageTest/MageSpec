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
 * @subpackage Matcher
 *
 * @copyright  Copyright (c) 2012-2013 MageTest team and contributors.
 */
namespace spec\MageTest\PhpSpec\MagentoExtension\Matcher;

use PhpSpec\ObjectBehavior;

/**
 * BeInAreaSpec
 *
 * @category   MageTest
 * @package    PhpSpec_MagentoExtension
 * @subpackage Loader
 *
 * @author     MageTest team (https://github.com/MageTest/MageSpec/contributors)
 */
class BeInAreaSpec extends ObjectBehavior
{
    /**
     * @param \PhpSpec\Formatter\Presenter\PresenterInterface $presenter
     */
    function let($presenter)
    {
        $this->beConstructedWith($presenter);
    }

    function it_is_a_matcher()
    {
        $this->shouldBeAnInstanceOf('PhpSpec\Matcher\BasicMatcher');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('MageTest\PhpSpec\MagentoExtension\Matcher\BeInArea');
    }

    /**
     * @param Mage_Core_Controller_Front_Action $controller
     */
//    function it_should_support_be_in_area_for_controllers($controller)
//    {
//        $this->supports('beInArea', $controller, array('admin'))
//             ->shouldBe(true);
//    }
}