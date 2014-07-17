<?php

namespace spec\MageTest\PhpSpec\MagentoExtension\Listener;

use MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml\ModuleGenerator;
use PhpSpec\Console\IO;
use PhpSpec\Event\ExampleEvent;
use PhpSpec\Event\SuiteEvent;
use PhpSpec\Exception\Fracture\ClassNotFoundException;
use PhpSpec\ObjectBehavior;
use PhpSpec\Util\Filesystem;
use Prophecy\Argument;

class ModuleUpdateListenerSpec extends ObjectBehavior
{
    function let(ModuleGenerator $moduleGenerator, IO $io)
    {
        $io->isCodeGenerationEnabled()->willReturn(true);
        $this->beConstructedWith($moduleGenerator, $io);
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

        $this->createModuleXmlAfterSuite($suiteEvent);

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

        $this->createModuleXmlAfterSuite($suiteEvent);
    }
}
