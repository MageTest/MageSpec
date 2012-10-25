<?php

namespace MageTest\PHPSpec2\MagentoExtension;

use MageTest\PHPSpec2\MagentoExtension\Bootstrap\App;

use Mage_Core_Model_App_Area,
    Mage_Core_Model_Config,
    Varien_Event_Collection;

use Mage_Core_Controller_Request_Http,
    Mage_Core_Controller_Response_Http;

class Bootstrap
{
    private $mageReflection;
    private $appReflection;
    private $app;

    function __construct(App $app)
    {
        $this->app = $app;
    }

    public function init($code = '', $type = 'store', $options = array(), $modules = array())
    {
        \Mage::reset();
        \Mage::setRoot();
        $this->setProtectedProperty('_app', $this->app);
        $this->setProtectedProperty('_config', new Mage_Core_Model_Config($options));

        if (!empty($modules)) {
            $this->getProtectedPropertyValue('_app')->initSpecified($code, $type, $options, $modules);
        } else {
            $this->getProtectedPropertyValue('_app')->init($code, $type, $options);
        }

        $app = $this->app();
        $app->setRequest(new Mage_Core_Controller_Request_Http);
        $app->setResponse(new Mage_Core_Controller_Response_Http);
    }

    public function run($code = '', $type = 'store', $options = array())
    {
        \Mage::setRoot();
        $this->setProtectedProperty('_app', $this->app);
        $this->setProtectedProperty('_events', new Varien_Event_Collection);
        $this->setProtectedProperty('_config', new Mage_Core_Model_Config($options));
        $this->getProtectedPropertyValue('_app')->run(array(
            'scope_code' => $code,
            'scope_type' => $type,
            'options'    => $options,
        ));

        $app = $this->app();
        $app->setRequest(new Mage_Core_Controller_Request_Http);
        $app->setResponse(new Mage_Core_Controller_Response_Http);
    }

    public function app($code = '', $type = 'store', $options = array())
    {
        if (is_null($this->getProtectedPropertyValue('_app'))) {
            \Mage::setRoot();
            $this->setProtectedProperty('_app', $this->app);
            $this->setProtectedProperty('_events', new Varien_Event_Collection);
            $this->setProtectedProperty('_config', new Mage_Core_Model_Config($options));

            $this->getProtectedPropertyValue('_app')->init($code, $type, $options);

            $this->getProtectedPropertyValue('_app')->loadAreaPart(
                Mage_Core_Model_App_Area::AREA_GLOBAL, 
                Mage_Core_Model_App_Area::PART_EVENTS
            );
        }
        return $this->getProtectedPropertyValue('_app');
    }

    public function getAppReflection()
    {
        if (is_null($this->appReflection)) {
            $this->appReflection = new \ReflectionClass('Mage_Core_Model_App');
        }

        return $this->appReflection;
    }

    public function getMageReflection()
    {
        if (is_null($this->mageReflection)) {
            $this->mageReflection = new \ReflectionClass('Mage');
        }

        return $this->mageReflection;
    }

    public function getProtectedProperty($name)
    {
        $property = $this->getMageReflection()->getProperty($name);
        $property->setAccessible(true);

        return $property;
    }

    public function getProtectedPropertyValue($name)
    {
        return $this->getProtectedProperty($name)->getValue();
    }

    public function setProtectedProperty($name, $value)
    {
        $property = $this->getProtectedProperty($name);
        $property->setValue($value);
    }
}
