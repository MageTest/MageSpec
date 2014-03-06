<?php

namespace MageTest\PhpSpec\MagentoExtension\Wrapper;

use PhpSpec\Formatter\Presenter\PresenterInterface;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Runner\MatcherManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class VarienWrapperFactory
{

    public function create(MatcherManager $matchers, PresenterInterface $presenter,
                           EventDispatcherInterface $dispatcher, ExampleNode $example)
    {
        return new VarienWrapper($matchers, $presenter, $dispatcher, $example);
    }
}
