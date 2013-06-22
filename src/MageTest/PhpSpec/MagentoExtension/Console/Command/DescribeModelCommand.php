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

use PhpSpec\Exception\Example\ErrorException;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * DescribeModelCommand
 *
 * @category   MageTest
 * @package    PhpSpec_MagentoExtension
 *
 * @author     MageTest team (https://github.com/MageTest/MageSpec/contributors)
 */
class DescribeModelCommand extends Command
{
    const VALIDATOR = '/^([a-zA-Z0-9]+)_([a-zA-Z0-9]+)\/([a-zA-Z0-9]+)(_[\w]+)?$/';

    public function __construct()
    {
        parent::__construct('describe:model');

        $this->setDefinition(array(
            new InputArgument('model_alias', InputArgument::REQUIRED, 'Magento Model alias to be described'),
        ));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $model = $input->getArgument('model_alias');

        if ((bool) preg_match(self::VALIDATOR, $model) === false) {
            $message = <<<ERR
The model alias provided doesn't follow the Magento naming conventions.
Please make sure it looks like the following:

  vendorname_modulename/modelname

Please pay attention to words case
ERR;
            throw new \InvalidArgumentException($message);
        }

        $container = $this->getApplication()->getContainer();
        $container->configure();

        $classname = 'model:' . $model;
        $resource  = $container->get('locator.resource_manager')->createResource($classname);

        $container->get('code_generator')->generate($resource, 'specification');
    }
}
