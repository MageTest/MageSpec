<?php 

class MyVendor_MyModule_Models_Adapters_ConfigAdapter
{
    /**
     * Return Magento Base Directory
     *
     * @return string
     */
    public function getBaseDir()
    {
        return Mage::getBaseDir();
    }
} 
