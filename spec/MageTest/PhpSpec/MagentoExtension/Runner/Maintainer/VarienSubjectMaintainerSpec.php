<?php

namespace spec\MageTest\PhpSpec\MagentoExtension\Runner\Maintainer;

use MageTest\PhpSpec\MagentoExtension\Wrapper\VarienWrapper;
use MageTest\PhpSpec\MagentoExtension\Wrapper\VarienWrapperFactory;
use PhpSpec\Formatter\Presenter\PresenterInterface;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Loader\Node\SpecificationNode;
use PhpSpec\Locator\ResourceInterface;
use PhpSpec\ObjectBehavior;
use PhpSpec\Runner\CollaboratorManager;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\Wrapper\Subject;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class VarienSubjectMaintainerSpec extends ObjectBehavior
{
    function let(PresenterInterface $presenter, EventDispatcherInterface $dispatcher, VarienWrapperFactory $factory)
    {
        $this->beConstructedWith($presenter, $dispatcher, $factory);
    }

    function it_prepares_the_subject(
        ExampleNode $example, ObjectBehavior $context, MatcherManager $matchers, CollaboratorManager $collaborators,
        SpecificationNode $specification, ResourceInterface $resource, VarienWrapper $wrapper, Subject $subject, $factory
    ) {
        $factory->create(Argument::cetera())->willReturn($wrapper);
        $wrapper->wrap(null)->willReturn($subject);

        $subject->beAnInstanceOf('\stdObject');
        $subject = $subject->getWrappedObject();

        $resource->getSrcClassname()->willReturn('\stdObject');
        $specification->getResource()->willReturn($resource);
        $example->getSpecification()->willReturn($specification);

        $context->setSpecificationSubject($subject)->shouldBeCalled();

        $this->prepare($example, $context, $matchers, $collaborators);
    }
}
