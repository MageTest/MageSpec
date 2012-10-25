<?php

namespace MageTest\PHPSpec2\MagentoExtension\Matcher;

use PHPSpec2\Matcher\BasicMatcher,
    PHPSpec2\Exception\Example\FailureException,
    PHPSpec2\Formatter\Representer\BasicRepresenter;

use PHPSpec2\Magento\ControllerSpecification;

class BeInArea extends BasicMatcher
{
    private $representer;

    public function __construct(RepresenterInterface $representer = null)
    {
        $this->representer = $representer ?: new BasicRepresenter;;
    }

    public function supports($alias, $subject, array $arguments)
    {
        return $alias === 'beInArea' &&
               $subject instanceof ControllerSpecification &&
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
            'Expected to be in area <value>%s</value>, got <value>%d</value>.',
            $this->representer->representValue($subject), $arguments[0], $area
        ));
    }

    protected function getNegativeFailureException($name, $subject, array $arguments)
    {
        $area = $subject->get('mage')->app()->getArea();
        return new FailureException(sprintf(
            'Expected not to be in area <value>%s</value>.',
            $this->representer->representValue($subject), $arguments[0], $area
        ));
    }
}