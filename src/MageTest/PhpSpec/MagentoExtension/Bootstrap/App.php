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
 * @subpackage Bootstrap
 *
 * @copyright  Copyright (c) 2012-2013 MageTest team and contributors.
 */
namespace MageTest\PhpSpec\MagentoExtension\Bootstrap;

use Mage_Core_Model_App as BaseApp,
    MageTest_Core_Controller_Front;

use Zend_Controller_Request_Abstract,
    Zend_Controller_Response_Abstract;

use PhpSpec\Magento\Bootstrap\App\HttpRequest as Mage_Core_Controller_Request_Http,
    PhpSpec\Magento\Bootstrap\App\HttpResponse as Mage_Core_Controller_Response_Http;

/**
 * App
 *
 * @category   MageTest
 * @package    PhpSpec_MagentoExtension
 * @subpackage Bootstrap
 *
 * @author     MageTest team (https://github.com/MageTest/MageSpec/contributors)
 */
class App extends BaseApp
{
    private $dispatchedEvents;

    public function __construct()
    {
        parent::__construct();
        $this->dispatchedEvents = array();
    }

    /**
     * Initialize application front controller
     *
     * @return Mage_Core_Model_App
     */
    protected function _initFrontController()
    {
        $this->_frontController = new MageTest_Core_Controller_Front();
        Mage::register('controller', $this->_frontController);
        $this->_frontController->init();

        return $this;
    }

    /**
     * Overridden for disabling events
     * fire during fixture loading
     *
     * @see Mage_Core_Model_App::dispatchEvent()
     * @return MageTest_Core_Model_App
     */
    public function dispatchEvent($eventName, $args)
    {
        parent::dispatchEvent($eventName, $args);

        if (!isset($this->dispatchedEvents[$eventName])) {
            $this->dispatchedEvents[$eventName] = 0;
        }

        $this->dispatchedEvents[$eventName]++;

        return $this;
    }

    /**
     * Returns number of times when the event was dispatched
     *
     * @param string $eventName
     * @return int
     */
    public function getDispatchedEventCount($eventName)
    {
        if (isset($this->dispatchedEvents[$eventName])) {
            return $this->dispatchedEvents[$eventName];
        }

        return 0;
    }

    /**
     * Resets dispatched events information
     *
     * @return MageTest_Core_Model_App
     */
    public function resetDispatchedEvents()
    {
        $this->dispatchedEvents = array();

        return $this;
    }
}
