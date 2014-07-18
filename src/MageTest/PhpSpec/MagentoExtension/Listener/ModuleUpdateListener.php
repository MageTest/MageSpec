<?php

namespace MageTest\PhpSpec\MagentoExtension\Listener;

use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\ConfigGenerator;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\ModuleGenerator;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\XmlGeneratorException;
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

    public function __construct(ModuleGenerator $moduleGenerator, ConfigGenerator $configGenerator, IO $io)
    {
        $this->moduleGenerator = $moduleGenerator;
        $this->configGenerator = $configGenerator;
        $this->io = $io;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'afterExample' => array('getClassNameAfterExample', 10),
            'afterSuite'   => array(
                array('createModuleXmlAfterSuite', -10),
                array('createConfigXmlAfterSuite', -10),
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
            $this->classNames[$className] = $this->getModuleName($className);
        }
    }

    public function createModuleXmlAfterSuite(SuiteEvent $event)
    {
        if (!$this->io->isCodeGenerationEnabled()) {
            return;
        }

        foreach (array_unique($this->classNames) as $moduleName) {
            $this->moduleGenerator->generate(($moduleName));
        }
    }

    public function createConfigXmlAfterSuite(SuiteEvent $event)
    {
        if (!$this->io->isCodeGenerationEnabled()) {
            return;
        }

        foreach ($this->classNames as $className => $moduleName) {
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
            throw new XmlGeneratorException('Could not determine a n object type from ' . $className);
        }
        return strtolower($parts[2]);
    }
}
