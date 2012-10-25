<?php

namespace MageTest\PHPSpec2\MagentoExtension\Matcher;

use PHPSpec2\Matcher\BasicMatcher,
    PHPSpec2\Exception\Example\FailureException;
    
use PHPSpec2\Formatter\Presenter\PresenterInterface;

use PHPSpec2\Magento\ControllerSpecification;

class BeInArea extends BasicMatcher
{
    private $representer;

    public function __construct(PresenterInterface $presenter)
    {
        $this->presenter = $presenter;
    }

    public function supports($alias, $subject, array $arguments)
    {
        return $alias === 'beInArea' &&
               $subject instanceof \Mage_Core_Controller_Front_Action &&
               isset($arguments[0]) &&
               in_array($arguments[0], array('admin', 'frontend'));
    }

    protected function matches($subject, array $arguments)
    {
        $subject->get('mage')->app()->getArea() === $arguments[0];
    }

    protected function getFailureException($name, $subject, array $arguments)
    {
        $area = $subject->get('mage')->app()->getArea();
        return new FailureException(sprintf(
            'Expected %s to be in area %s, got %s.',
            $this->presenter->presentValue($subject),
            $this->presenter->presentString($arguments[0]),
            $this->presenter->presentString($area)
        ));
    }

    protected function getNegativeFailureException($name, $subject, array $arguments)
    {
        $area = $subject->get('mage')->app()->getArea();
        return new FailureException(sprintf(
            'Expected %s not to be in area %s.',
            $this->presenter->presentValue($subject),
            $this->presenter->presentValue($arguments[0])
        ));
    }
}