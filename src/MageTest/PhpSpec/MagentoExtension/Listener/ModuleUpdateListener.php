<?php

namespace MageTest\PhpSpec\MagentoExtension\Listener;

use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\ConfigGenerator;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\ModuleGenerator;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\XmlGeneratorException;
use MageTest\PhpSpec\MagentoExtension\Util\ClassDetector;
use PhpSpec\Console\ConsoleIO as IO;
use PhpSpec\Event\ExampleEvent;
use PhpSpec\Event\SuiteEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use PhpSpec\Exception\Fracture\ClassNotFoundException as PhpSpecClassException;
use Prophecy\Exception\Doubler\ClassNotFoundException as ProphecyClassException;

class ModuleUpdateListener implements EventSubscriberInterface
{
    /**
     * @var array
     */
    private $classNames = array();

    /**
     * @var ModuleGenerator
     */
    private $moduleGenerator;

    /**
     * @var ConfigGenerator
     */
    private $configGenerator;

    /**
     * @var IO
     */
    private $io;

    /**
     * @var ClassDetector
     */
    private $detector;

    /**
     * @param ModuleGenerator $moduleGenerator
     * @param ConfigGenerator $configGenerator
     * @param IO $io
     * @param ClassDetector $detector
     */
    public function __construct(
        ModuleGenerator $moduleGenerator,
        ConfigGenerator $configGenerator,
        IO $io,
        ClassDetector $detector
    ) {
        $this->moduleGenerator = $moduleGenerator;
        $this->configGenerator = $configGenerator;
        $this->io = $io;
        $this->detector = $detector;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            'afterExample' => array('getClassNameAfterExample', 10),
            'afterSuite'   => array('createXmlAfterSuite', -20),
        );
    }

    /**
     * @param ExampleEvent $event
     */
    public function getClassNameAfterExample(ExampleEvent $event)
    {
        $exception = $event->getException();

        if ($this->exceptionIsNotUsable($exception)) {
            return;
        }

        $className = $exception->getClassname();

        if (strlen($className)) {
            $parts = explode('_', $className);
            if (!isset($parts[0]) || !isset($parts[1])) {
                return;
            }
            $this->classNames[$className] = $parts[0] . '_' . $parts[1];
        }
    }

    /**
     * @param SuiteEvent $event
     */
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

    /**
     * @param string $className
     * @return string
     */
    private function getClassType($className)
    {
        $parts = explode('_', $className);
        if (!isset($parts[2])) {
            throw new XmlGeneratorException('Could not determine an object type from ' . $className);
        }
        if ($this->partIsController($parts[count($parts)-1])) {
            return 'controller';
        }
        return strtolower($parts[2]);
    }

    /**
     * @param string $part
     * @return bool
     */
    private function partIsController($part)
    {
        $element = 'Controller';
        return strlen($part) - strlen($element) === strrpos($part, $element);
    }

    /**
     * @param \Exception $exception
     * @return bool
     */
    protected function exceptionIsNotUsable($exception)
    {
        if (null === $exception) {
            return true;
        }

        if (!($exception instanceof PhpSpecClassException || $exception instanceof ProphecyClassException)) {
            return true;
        }

        return false;
    }
}
