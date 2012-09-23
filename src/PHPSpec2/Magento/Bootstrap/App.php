<?php

namespace PHPSpec2\Magento\Bootstrap;

use Mage_Core_Model_App as BaseApp,
    MageTest_Core_Controller_Front;

use Zend_Controller_Request_Abstract,
    Zend_Controller_Response_Abstract;

use PHPSpec2\Magento\Bootstrap\App\HttpRequest as Mage_Core_Controller_Request_Http,
    PHPSpec2\Magento\Bootstrap\App\HttpResponse as Mage_Core_Controller_Response_Http;

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
