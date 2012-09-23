<?php

namespace PHPSpec2\Magento\Matcher;

use PHPSpec2\Matcher\BasicMatcher;

use PHPSpec2\Magento\ControllerSpecification;

class BeInArea extends BasicMatcher
{

    public function supports($alias, $subject, array $arguments)
    {
        return $alias === 'beInArea' &&
               $subject instanceof ControllerSpecification &&
               isset($arguments[0]) &&
               in_array($arguments[0], array('admin', 'frontend'));
    }

    protected function matches($subject, array $arguments)
    {
        $this->subject->get('mage.app')->getArea() === $arguments[0];
    }

    protected function getFailureException($name, $subject, array $arguments)
    {
        
    }

    protected function getNegativeFailureException($name, $subject, array $arguments)
    {
        
    }
}