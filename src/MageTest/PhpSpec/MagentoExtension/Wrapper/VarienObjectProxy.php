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

use PhpSpec\Exception\Fracture\ClassNotFoundException;
use PhpSpec\Exception\Fracture\MethodNotFoundException;

/**
 * VarienObjectProxy
 *
 * @category   MageTest
 * @package    PhpSpec_MagentoExtension
 *
 * @author     MageTest team (https://github.com/MageTest/MageSpec/contributors)
 */
class VarienObjectProxy
{
    private $classname;
    private $object;
    private $presenter;

    public function __construct($classname, $presenter)
    {
        $this->classname = $classname;
        $this->presenter = $presenter;
    }

    public function __call($method, $args)
    {
        $object = $this->getVarienObject();
        if (!method_exists($object, $method)) {
            throw new MethodNotFoundException(sprintf(
                'Method %s not found.',
                $this->presenter->presentString(get_class($object).'::'.$method.'()')
            ), $object, $method, $args);
        }

        return call_user_func_array(array($object, $method), $args);
    }

    public function getVarienObject()
    {
        if ($this->object === null) {
            if (!class_exists($this->classname)) {
                throw new ClassNotFoundException(sprintf(
                    'Class %s does not exist.', $this->presenter->presentString($this->classname)
                ), $this->classname);
            }

            $this->object = new $this->classname;
        }
        return $this->object;
    }
}