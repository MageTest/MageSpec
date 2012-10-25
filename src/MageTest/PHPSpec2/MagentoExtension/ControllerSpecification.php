<?php

namespace MageTest\PHPSpec2\MagentoExtension;

use PHPSpec2\SpecificationInterface;
use PHPSpec2\Prophet\ProphetInterface;

//require_once __DIR__ . DIRECTORY_SEPARATOR . "SpecHelper.php"; 

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