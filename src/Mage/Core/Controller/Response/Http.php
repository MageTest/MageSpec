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
 * @category   Mage
 * @package    Core
 * @subpackage Controller
 *
 * @copyright  Copyright (c) 2012-2013 MageTest team and contributors.
 */

/**
 * Mage_Core_Controller_Response_Http
 *
 * @category   Mage
 * @package    Core
 * @subpackage Controller
 *
 * @author     MageTest team (https://github.com/MageTest/MageSpec/contributors)
 */
class Mage_Core_Controller_Response_Http extends Zend_Controller_Response_HttpTestCase
{
	public function sendResponse()
    {
        Mage::dispatchEvent('http_response_send_before', array('response'=>$this));
        return parent::sendResponse();
    }
}