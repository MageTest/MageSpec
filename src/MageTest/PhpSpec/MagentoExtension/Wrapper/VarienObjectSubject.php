<?php
/**
 * [application]
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Apache License, Version 2.0 that is
 * bundled with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to <${EMAIL}> so we can send you a copy immediately.
 *
 * @category   [category]
 * @package    [package]
 * @copyright  Copyright (c) 2012 debo <${EMAIL}> (${URL})
 */

/**
 * [name]
 *
 * @category   [category]
 * @package    [package]
 * @author     debo <${EMAIL}> (${URL})
 */

namespace MageTest\PhpSpec\MagentoExtension\Wrapper;

use PhpSpec\Wrapper\Subject;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\Wrapper\Unwrapper;
use PhpSpec\Formatter\Presenter\PresenterInterface;

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
