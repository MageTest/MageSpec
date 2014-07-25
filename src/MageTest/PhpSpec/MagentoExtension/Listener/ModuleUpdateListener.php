<?php

namespace MageTest\PhpSpec\MagentoExtension\Listener;

use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\ConfigGenerator;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\ModuleGenerator;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\XmlGeneratorException;
use MageTest\PhpSpec\MagentoExtension\Util\ClassDetector;
use PhpSpec\Console\IO;
use PhpSpec\Event\ExampleEvent;
use PhpSpec\Event\SuiteEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use PhpSpec\Exception\Fracture\ClassNotFoundException as PhpSpecClassException;
use Prophecy\Exception\Doubler\ClassNotFoundException as ProphecyClassException;

class ModuleUpdateListener implements EventSubscriberInterface
{
    private $classNames = array();
    private $moduleGenerator;
    private $configGenerator;
    private $io;
    private $detector;

    public function __construct(
        ModuleGenerator $moduleGenerator, ConfigGenerator $configGenerator, IO $io, ClassDetector $detector)
    {
        $this->moduleGenerator = $moduleGenerator;
        $this->configGenerator = $configGenerator;
        $this->io = $io;
        $this->detector = $detector;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'afterExample' => array('getClassNameAfterExample', 10),
            'afterSuite'   => array('createXmlAfterSuite', -20),
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
            $this->classNames[$className] = $this->getModuleName($className);
        }
    }

    public function createXmlAfterSuite(SuiteEvent $event)
    {
        if (!$this->io->isCodeGenerationEnabled()) {
            return;
        }

        foreach ($this->classNames as $className => $moduleName) {
            if (!$this->detector->classExists($className)) {
                continue;
            }

            $this->moduleGenerator->generate(($moduleName));

            $this->configGenerator->generateElement(
                $this->getClassType($className),
                $moduleName
            );
        }
    }

    private function getModuleName($className)
    {
        $parts = explode('_', $className);
        if (!isset($parts[0]) || !isset($parts[1])) {
            throw new XmlGeneratorException('Could not determine a module name from ' . $className);
        }
        return $parts[0] . '_' . $parts[1];
    }

    private function getClassType($className)
    {
        $parts = explode('_', $className);
        if (!isset($parts[2])) {
            throw new XmlGeneratorException('Could not determine an object type from ' . $className);
        }
        if ($parts[2] === 'Model' && $parts[3] === 'Resource') {
            return 'resource_model';
        }
        if ($this->partIsController($parts[count($parts)-1])) {
            return 'controller';
        }
        return strtolower($parts[2]);
    }

    private function partIsController($part)
    {
        $element = 'Controller';
        return strlen($part) - strlen($element) === strrpos($part, $element);
    }
}
