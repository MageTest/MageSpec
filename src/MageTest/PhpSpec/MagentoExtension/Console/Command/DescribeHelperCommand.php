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

use Symfony\Component\Console\Input\InputArgument;

/**
 * DescribeHelperCommand
 *
 * @category   MageTest
 * @package    PhpSpec_MagentoExtension
 *
 * @author     MageTest team (https://github.com/MageTest/MageSpec/contributors)
 */
class DescribeHelperCommand extends MageCommand
{
    /**
     * @var string
     */
    protected $validator = '/^([a-zA-Z0-9]+)_([a-zA-Z0-9]+)\/([a-zA-Z0-9]+)(_[\w]+)?$/';

    /**
     * @var string
     */
    protected $help = <<<HELP
The helper alias provided doesn't follow the Magento naming conventions.
Please make sure it looks like the following:

  vendorname_modulename/helpername

The lowercase convention is used because it reflects the best practice
convention within the Magento community. This reflects the identifier that
you would pass to Mage::helper().
HELP;

    /**
     * @var string
     */
    protected $type = 'helper';

    protected function configure()
    {
        $this->setName('describe:helper')
            ->setDescription('Describe a Magento Helper specification')
            ->addArgument('alias', InputArgument::REQUIRED, 'Magento Helper alias to be described');
    }
}
