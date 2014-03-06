<?php

namespace spec\MageTest\PhpSpec\MagentoExtension\Wrapper;

use PhpSpec\Formatter\Presenter\PresenterInterface;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\ObjectBehavior;
use PhpSpec\Runner\MatcherManager;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class VarienWrapperFactorySpec extends ObjectBehavior
{
    function it_returns_a_varienwrapper(MatcherManager $matchers, PresenterInterface $presenter,
                                        EventDispatcherInterface $dispatcher, ExampleNode $example)
    {
        $this->create($matchers, $presenter, $dispatcher, $example)
            ->shouldReturnAnInstanceOf('MageTest\PhpSpec\MagentoExtension\Wrapper\VarienWrapper');
    }
}
