<?php 

class MyVendor_MyModule_Blocks_Directory
{
    private $_configAdapter;

    /**
     * @param array $services
     */
    public function __construct(array $services = array())
    {
        if (isset($services['config_adapter'])) {
            $this->_configAdapter = $services['config_adapter'];
        }
        if (!$this->_configAdapter instanceof MyVendor_MyModule_Models_Adapters_ConfigAdapter) {
            $this->_configAdapter = new MyVendor_MyModule_Models_Adapters_ConfigAdapter();
        }
    }

    public function getBaseDirectory()
    {
        return $this->_configAdapter->getBaseDir();
    }
} 
