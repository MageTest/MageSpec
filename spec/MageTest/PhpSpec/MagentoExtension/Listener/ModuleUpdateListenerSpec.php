<?php

namespace spec\MageTest\PhpSpec\MagentoExtension\Listener;

use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\ConfigGenerator;
use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\ModuleGenerator;
use MageTest\PhpSpec\MagentoExtension\Util\ClassDetector;
use PhpSpec\Console\IO;
use PhpSpec\Event\ExampleEvent;
use PhpSpec\Event\SuiteEvent;
use PhpSpec\Exception\Fracture\ClassNotFoundException;
use PhpSpec\Locator\ResourceInterface;
use PhpSpec\ObjectBehavior;
use PhpSpec\Util\Filesystem;
use Prophecy\Argument;

class ModuleUpdateListenerSpec extends ObjectBehavior
{
    function let(ModuleGenerator $moduleGenerator, ConfigGenerator $configGenerator, IO $io, ClassDetector $detector)
    {
        $io->isCodeGenerationEnabled()->willReturn(true);
        $detector->classExists(Argument::any())->willReturn(true);
        $this->beConstructedWith($moduleGenerator, $configGenerator, $io, $detector);
    }

    function it_is_an_event_subscriber()
    {
        $this->shouldHaveType('Symfony\Component\EventDispatcher\EventSubscriberInterface');
    }

    function it_extracts_class_names_from_event_exceptions(ExampleEvent $event, ClassNotFoundException $exception)
    {
        $event->getException()->willReturn($exception);

        $this->getClassNameAfterExample($event);

        $exception->getClassname()->shouldHaveBeenCalled();
    }

    function it_generates_a_module_xml(
        ExampleEvent $exampleEvent, ClassNotFoundException $exception, SuiteEvent $suiteEvent, $moduleGenerator
    ){
        $exampleEvent->getException()->willReturn($exception);
        $exception->getClassname()->willReturn('Vendor_Module_Model_Foo');
        $this->getClassNameAfterExample($exampleEvent);

        $this->createXmlAfterSuite($suiteEvent);

        $moduleGenerator->generate('Vendor_Module')->shouldHavebeenCalled();
    }

    function it_does_not_generate_a_module_xml_if_code_generation_is_disabled(
        ExampleEvent $exampleEvent, ClassNotFoundException $exception, SuiteEvent $suiteEvent, $moduleGenerator, $io
    ) {
        $exampleEvent->getException()->willReturn($exception);
        $exception->getClassname()->willReturn('Vendor_Module_Model_Foo');
        $this->getClassNameAfterExample($exampleEvent);

        $io->isCodeGenerationEnabled()->willReturn(false);

        $moduleGenerator->moduleFileExists('Vendor_Module')->shouldNotBeCalled();
        $moduleGenerator->generate('Vendor_Module')->shouldNotBeCalled();

        $this->createXmlAfterSuite($suiteEvent);
    }

    function it_does_not_generate_a_module_xml_if_the_class_does_not_exist(
        ExampleEvent $exampleEvent, ClassNotFoundException $exception, SuiteEvent $suiteEvent, $moduleGenerator, $detector
    ) {
        $exampleEvent->getException()->willReturn($exception);
        $exception->getClassname()->willReturn('Vendor_Module_Model_Foo');
        $this->getClassNameAfterExample($exampleEvent);

        $detector->classExists('Vendor_Module_Model_Foo')->willReturn(false);

        $moduleGenerator->moduleFileExists('Vendor_Module')->shouldNotBeCalled();
        $moduleGenerator->generate('Vendor_Module')->shouldNotBeCalled();

        $this->createXmlAfterSuite($suiteEvent);
    }

    function it_generates_a_config_xml(
        ExampleEvent $exampleEvent, ClassNotFoundException $exception, SuiteEvent $suiteEvent, $configGenerator
    ){
        $exampleEvent->getException()->willReturn($exception);
        $exception->getClassname()->willReturn('Vendor_Module_Model_Foo');
        $this->getClassNameAfterExample($exampleEvent);

        $this->createXmlAfterSuite($suiteEvent);

        $configGenerator->generateElement('model', 'Vendor_Module')->shouldHavebeenCalled();
    }

    function it_identifies_a_resource_model_type(
        ExampleEvent $exampleEvent, ClassNotFoundException $exception, SuiteEvent $suiteEvent, $configGenerator
    ) {
        $exampleEvent->getException()->willReturn($exception);
        $exception->getClassname()->willReturn('Vendor_Module_Model_Resource_Foo');
        $this->getClassNameAfterExample($exampleEvent);

        $this->createXmlAfterSuite($suiteEvent);

        $configGenerator->generateElement('resource_model', 'Vendor_Module')->shouldHavebeenCalled();
    }
}
