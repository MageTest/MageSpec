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
namespace MageTest\PhpSpec\MagentoExtension\Matcher;

use PhpSpec\Matcher\BasicMatcher,
    PhpSpec\Exception\Example\FailureException;

use PhpSpec\Formatter\Presenter\PresenterInterface;

use PhpSpec\Magento\ControllerSpecification;

/**
 * BeInArea
 *
 * @category   MageTest
 * @package    PhpSpec_MagentoExtension
 * @subpackage Matcher
 *
 * @author     MageTest team (https://github.com/MageTest/MageSpec/contributors)
 */
class BeInArea extends BasicMatcher
{
    private $representer;

    public function __construct(PresenterInterface $presenter)
    {
        $this->presenter = $presenter;
    }

    public function supports($alias, $subject, array $arguments)
    {
        return $alias === 'beInArea' &&
               $subject instanceof \Mage_Core_Controller_Front_Action &&
               isset($arguments[0]) &&
               in_array($arguments[0], array('admin', 'frontend'));
    }

    protected function matches($subject, array $arguments)
    {
        $subject->get('mage')->app()->getArea() === $arguments[0];
    }

    protected function getFailureException($name, $subject, array $arguments)
    {
        $area = $subject->get('mage')->app()->getArea();
        return new FailureException(sprintf(
            'Expected %s to be in area %s, got %s.',
            $this->presenter->presentValue($subject),
            $this->presenter->presentString($arguments[0]),
            $this->presenter->presentString($area)
        ));
    }

    protected function getNegativeFailureException($name, $subject, array $arguments)
    {
        $area = $subject->get('mage')->app()->getArea();
        return new FailureException(sprintf(
            'Expected %s not to be in area %s.',
            $this->presenter->presentValue($subject),
            $this->presenter->presentValue($arguments[0])
        ));
    }
}