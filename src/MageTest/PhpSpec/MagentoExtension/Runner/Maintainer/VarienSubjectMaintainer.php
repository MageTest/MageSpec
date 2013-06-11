<?php
/**
 * MageSpec
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License, that is bundled with this
 * package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 *
 * http://opensource.org/licenses/MIT
 *
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world-wide-web, please send an email
 * to <magetest@sessiondigital.com> so we can send you a copy immediately.
 *
 * @category   MageTest
 * @package    PhpSpec_MagentoExtension
 *
 * @copyright  Copyright (c) 2012-2013 MageTest team and contributors.
 */
namespace MageTest\PhpSpec\MagentoExtension\Runner\Maintainer;

use MageTest\PhpSpec\MagentoExtension\Locator\Magento\ModelResource;
use MageTest\PhpSpec\MagentoExtension\Locator\Magento\ResourceModelResource;
use MageTest\PhpSpec\MagentoExtension\Locator\Magento\BlockResource;
use MageTest\PhpSpec\MagentoExtension\Locator\Magento\HelperResource;
use MageTest\PhpSpec\MagentoExtension\Locator\Magento\ControllerResource;
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
 * VarienSubjectMaintainer
 *
 * @category   MageTest
 * @package    PhpSpec_MagentoExtension
 *
 * @author     MageTest team (https://github.com/MageTest/MageSpec/contributors)
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
        $resource = $example->getSpecification()->getResource();

        return $resource instanceof ModelResource ||
               $resource instanceof ResourceModelResource ||
               $resource instanceof BlockResource ||
               $resource instanceof HelperResource ||
               $resource instanceof ControllerResource;
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
