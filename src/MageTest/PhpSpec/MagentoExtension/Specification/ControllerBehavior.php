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
 * @subpackage Specification
 *
 * @copyright  Copyright (c) 2012-2013 MageTest team and contributors.
 */
namespace MageTest\PhpSpec\MagentoExtension\Specification;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Zend_Controller_Request_Abstract as Request;
use Zend_Controller_Response_Abstract as Response;

/**
 * ControllerBehavior
 *
 * @category   MageTest
 * @package    PhpSpec_MagentoExtension
 * @subpackage Specification
 *
 * @author     MageTest team (https://github.com/MageTest/MageSpec/contributors)
 */
abstract class ControllerBehavior extends ObjectBehavior
{
    function let(Request $request, Response $response)
    {
        $this->beConstructedWith($request, $response);
    }
}
