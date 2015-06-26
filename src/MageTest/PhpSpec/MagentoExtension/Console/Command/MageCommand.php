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
namespace MageTest\PhpSpec\MagentoExtension\Console\Command;

use PhpSpec\Locator\ResourceManagerInterface;
use PhpSpec\ServiceContainer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class MageCommand extends Command
{
    /**
     * @var string
     */
    protected $validator;

    /**
     * @var string
     */
    protected $help;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var ServiceContainer
     */
    private $container;

    public function __construct(ServiceContainer $container)
    {
        $this->container = $container;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $alias = $input->getArgument('alias');

        if ((bool) preg_match($this->validator, $alias) === false) {
            throw new \InvalidArgumentException($this->help);
        }

        $this->container->configure();

        $classname = $this->type . ':' . $alias;
        $resource  = $this->container->get('locator.resource_manager')->createResource($classname);

        $this->container->get('code_generator')->generate($resource, 'specification');
    }
} 
