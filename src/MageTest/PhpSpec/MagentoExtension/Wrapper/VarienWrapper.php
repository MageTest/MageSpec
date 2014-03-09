<?php

namespace MageTest\PhpSpec\MagentoExtension\Wrapper;

use MageTest\PhpSpec\MagentoExtension\Wrapper\Subject\VarienCaller;
use PhpSpec\Exception\ExceptionFactory;
use PhpSpec\Formatter\Presenter\PresenterInterface;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\Wrapper\Subject\ExpectationFactory;
use PhpSpec\Wrapper\Subject\SubjectWithArrayAccess;
use PhpSpec\Wrapper\Subject\WrappedObject;
use PhpSpec\Wrapper\Subject;
use PhpSpec\Wrapper\Wrapper;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class VarienWrapper extends Wrapper
{
    /**
     * @var \PhpSpec\Runner\MatcherManager
     */
    private $matchers;

    /**
     * @var \PhpSpec\Formatter\Presenter\PresenterInterface
     */
    private $presenter;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var \PhpSpec\Loader\Node\ExampleNode
     */
    private $example;

    /**
     * Constructor
     *
     * @param MatcherManager           $matchers
     * @param PresenterInterface       $presenter
     * @param EventDispatcherInterface $dispatcher
     * @param ExampleNode              $example
     */
    public function __construct(MatcherManager $matchers, PresenterInterface $presenter,
                                EventDispatcherInterface $dispatcher, ExampleNode $example)
    {
        $this->matchers = $matchers;
        $this->presenter = $presenter;
        $this->dispatcher = $dispatcher;
        $this->example = $example;
    }

    /**
     * Replaces the default Wrapper::wrap implementation to allow for a
     * customised Caller object.
     *
     * @param mixed $value
     * @return Subject
     */
    public function wrap($value = null)
    {
        $exceptionFactory   = new ExceptionFactory($this->presenter);
        $wrappedObject      = new WrappedObject($value, $this->presenter);
        $caller             = new VarienCaller($wrappedObject, $this->example, $this->dispatcher, $exceptionFactory, $this);
        $arrayAccess        = new SubjectWithArrayAccess($caller, $this->presenter, $this->dispatcher);
        $expectationFactory = new ExpectationFactory($this->example, $this->dispatcher, $this->matchers);

        return new Subject(
            $value, $this, $wrappedObject, $caller, $arrayAccess, $expectationFactory
        );
    }
}
