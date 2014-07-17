<?php

namespace spec\MageTest\PhpSpec\MagentoExtension\Listener;

use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\ModuleGenerator;
use PhpSpec\Event\ExampleEvent;
use PhpSpec\Event\SuiteEvent;
use PhpSpec\Exception\Fracture\ClassNotFoundException;
use PhpSpec\ObjectBehavior;
use PhpSpec\Util\Filesystem;
use Prophecy\Argument;

class ModuleUpdateListenerSpec extends ObjectBehavior
{
    function let(ModuleGenerator $moduleGenerator)
    {
        $this->beConstructedWith($moduleGenerator);
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

    function it_generates_a_module_xml_if_needed(
        ExampleEvent $exampleEvent, ClassNotFoundException $exception, SuiteEvent $suiteEvent, $moduleGenerator
    ){
        $exampleEvent->getException()->willReturn($exception);
        $exception->getClassname()->willReturn('Vendor_Module_Model_Foo');
        $this->getClassNameAfterExample($exampleEvent);

        $moduleGenerator->moduleFileExists('Vendor_Module')->willReturn(false);
        $moduleGenerator->generate('Vendor_Module')->shouldBeCalled();

        $this->createModuleXmlAfterSuite($suiteEvent);
    }

    function it_does_not_generate_a_module_xml_if_one_exists(
        ExampleEvent $exampleEvent, ClassNotFoundException $exception, SuiteEvent $suiteEvent, $moduleGenerator
    ){
        $exampleEvent->getException()->willReturn($exception);
        $exception->getClassname()->willReturn('Vendor_Module_Model_Foo');
        $this->getClassNameAfterExample($exampleEvent);

        $moduleGenerator->moduleFileExists('Vendor_Module')->willReturn(true);
        $moduleGenerator->generate('Vendor_Module')->shouldNotBeCalled();

        $this->createModuleXmlAfterSuite($suiteEvent);
    }
}
