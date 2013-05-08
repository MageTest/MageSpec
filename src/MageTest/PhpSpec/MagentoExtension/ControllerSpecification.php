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
namespace MageTest\PhpSpec\MagentoExtension;

use PhpSpec\SpecificationInterface;
use PhpSpec\Prophet\ProphetInterface;

//require_once __DIR__ . DIRECTORY_SEPARATOR . "SpecHelper.php";

/**
 * ControllerSpecification
 *
 * @category   MageTest
 * @package    PhpSpec_MagentoExtension
 *
 * @author     MageTest team (https://github.com/MageTest/MageSpec/contributors)
 */
class ControllerSpecification extends ContainerAwareSpecification implements SpecificationInterface
{
    protected $controller;

    // public function __call($method, $args)
    // {
    //     if ($this->isAction($method)) {
    //         $this->_dispatch($method);
    //     }
    // }

    // public function __get($attribute)
    // {
    //     if ($attribute === $this->className) {
    //         //$this->$attribute = $this->createProphet($this->$attribute);
    //     }
    // }



    public function setProphet(ProphetInterface $prophet)
    {
        $this->controller = $prophet;
    }
    //
    // private function _dispatch($action)
    // {
    //
    // }
    //
    // private function isAction($method)
    // {
    //
    // }
}