<?php
/**
 * [application]
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Apache License, Version 2.0 that is
 * bundled with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to <${EMAIL}> so we can send you a copy immediately.
 *
 * @category   [category]
 * @package    [package]
 * @copyright  Copyright (c) 2012 debo <${EMAIL}> (${URL})
 */
namespace MageTest\PhpSpec\MagentoExtension\Runner\Maintainer;

use MageTest\PhpSpec\MagentoExtension\Locator\Magento\ModelResource;
use MageTest\PhpSpec\MagentoExtension\Wrapper\VarienObjectProxy;
use MageTest\PhpSpec\MagentoExtension\Wrapper\VarienObjectSubject;
use PhpSpec\Formatter\Presenter\PresenterInterface;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Runner\CollaboratorManager;
use PhpSpec\Runner\Maintainer\MaintainerInterface;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\SpecificationInterface;
use PhpSpec\Wrapper\Subject;
use PhpSpec\Wrapper\Unwrapper;

/**
 * [name]
 *
 * @category   [category]
 * @package    [package]
 * @author     debo <${EMAIL}> (${URL})
 */

class VarienSubjectMaintainer implements MaintainerInterface
{
    private $presenter;
    private $unwrapper;

    public function __construct(PresenterInterface $presenter, Unwrapper $unwrapper)
    {
        $this->presenter = $presenter;
        $this->unwrapper = $unwrapper;
    }

    public function supports(ExampleNode $example)
    {
        return $example->getSpecification()->getResource() instanceof ModelResource;
    }

    public function prepare(ExampleNode $example, SpecificationInterface $context,
                            MatcherManager $matchers, CollaboratorManager $collaborators)
    {
        $className = $example->getSpecification()->getResource()->getSrcClassname();

        $varienProxy  = new VarienObjectProxy($className, $this->presenter);

        $subject = new VarienObjectSubject($varienProxy, $matchers, $this->unwrapper, $this->presenter);

        $context->setSpecificationSubject($subject);
    }

    public function teardown(ExampleNode $example, SpecificationInterface $context,
                             MatcherManager $matchers, CollaboratorManager $collaborators)
    {
    }

    public function getPriority()
    {
        return 90;
    }
}
