<?php

namespace MageTest\PhpSpec\MagentoExtension\Listener;

use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\ModuleGenerator;
use PhpSpec\Event\ExampleEvent;
use PhpSpec\Event\SuiteEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use PhpSpec\Exception\Fracture\ClassNotFoundException as PhpSpecClassException;
use Prophecy\Exception\Doubler\ClassNotFoundException as ProphecyClassException;

class ModuleUpdateListener implements EventSubscriberInterface
{
    private $classNames = array();
    private $moduleGenerator;

    public function __construct(ModuleGenerator $moduleGenerator)
    {
        $this->moduleGenerator = $moduleGenerator;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'afterExample' => array('getClassNameAfterExample', 10),
            'afterSuite'   => array(
                array('createModuleXmlAfterSuite', -10),
                //array('createConfigXmlAfterSuite', -10),
            ),
        );
    }

    public function getClassNameAfterExample(ExampleEvent $event)
    {
        if (null === $exception = $event->getException()) {
            return;
        }

        if (!($exception instanceof PhpSpecClassException) &&
            !($exception instanceof ProphecyClassException)) {
            return;
        }

        $className = $exception->getClassname();
        if (strlen($className)) {
            $this->classNames[$this->getModuleName($className)] = $className;
        }
    }

    public function createModuleXmlAfterSuite(SuiteEvent $event)
    {
        foreach (array_unique(array_keys($this->classNames)) as $moduleName) {
            if (!$this->moduleGenerator->moduleFileExists($moduleName)) {
                $this->moduleGenerator->generate(($moduleName));
            }
        }
    }

    private function getModuleName($className)
    {
        $parts = explode('_', $className);
        return $parts[0] . '_' . $parts[1];
    }
}
