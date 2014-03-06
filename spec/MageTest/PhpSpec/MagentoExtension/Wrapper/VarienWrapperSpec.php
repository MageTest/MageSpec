<?php

namespace spec\MageTest\PhpSpec\MagentoExtension\Wrapper;

use PhpSpec\Formatter\Presenter\PresenterInterface;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\ObjectBehavior;
use PhpSpec\Runner\MatcherManager;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class VarienWrapperSpec extends ObjectBehavior
{
    function let(MatcherManager $matchers, PresenterInterface $presenter,
                 EventDispatcherInterface $dispatcher, ExampleNode $example)
    {
        $this->beConstructedWith($matchers, $presenter, $dispatcher, $example);
    }

    function it_should_return_a_wrapped_object()
    {
        $this->wrap()->shouldImplement('PhpSpec\Wrapper\WrapperInterface');
    }
}
