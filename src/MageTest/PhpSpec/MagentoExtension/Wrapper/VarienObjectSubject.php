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
namespace MageTest\PhpSpec\MagentoExtension\Wrapper;

use PhpSpec\Wrapper\Subject;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\Wrapper\Unwrapper;
use PhpSpec\Formatter\Presenter\PresenterInterface;
use PhpSpec\Exception\Fracture\MethodNotFoundException;

/**
 * VarienObjectSubject
 *
 * @category   MageTest
 * @package    PhpSpec_MagentoExtension
 *
 * @author     MageTest team (https://github.com/MageTest/MageSpec/contributors)
 */
class VarienObjectSubject extends Subject
{
    private $presenter;

    public function __construct($subject, MatcherManager $matchers, Unwrapper $unwrapper,
                                PresenterInterface $presenter)
    {
        $this->presenter = $presenter;

        parent::__construct($subject, $matchers, $unwrapper, $presenter);
    }

    public function callOnWrappedObject($method, array $args = array())
    {
        if (null === $object = $this->getWrappedObject()) {
            return parent::callOnWrappedObject($method, $args);
        }

        if (!method_exists($object, $method)) {
            throw new MethodNotFoundException(sprintf(
                'Method %s not found.',
                $this->presenter->presentString(get_class($object).'::'.$method.'()')
            ), $object, $method, $args);
        }

        return parent::callOnWrappedObject($method, $args);
    }
}
