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
namespace MageTest\PhpSpec\MagentoExtension\Wrapper;

use PhpSpec\Exception\Fracture\ClassNotFoundException;
use PhpSpec\Exception\Fracture\MethodNotFoundException;
/**
 * [name]
 *
 * @category   [category]
 * @package    [package]
 * @author     debo <${EMAIL}> (${URL})
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