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
namespace MageTest\PhpSpec\MagentoExtension\Locator\Magento;

use PhpSpec\Locator\ResourceLocatorInterface;

/**
 * HelperLocator
 *
 * @category   MageTest
 * @package    PhpSpec_MagentoExtension
 *
 * @author     MageTest team (https://github.com/MageTest/MageSpec/contributors)
 */
class HelperLocator extends AbstractResourceLocator implements ResourceLocatorInterface
{
    protected $classType = 'Helper';
    protected $validator = '/^(helper):([a-zA-Z0-9]+)_([a-zA-Z0-9]+)\/([a-zA-Z0-9]+)(_[\w]+)?$/';

    /**
     * @return int
     */
    public function getPriority()
    {
        return 20;
    }

    /**
     * @param string $file
     * @return bool
     */
    protected function isSupported($file)
    {
        return strpos($file, 'Helper') > 0;
    }

    /**
     * @param array $parts
     * @param ResourceLocatorInterface $locator
     * @return HelperResource
     * @throws \InvalidArgumentException
     */
    protected function getResource(array $parts, ResourceLocatorInterface $locator)
    {
        if (!$locator instanceof HelperLocator) {
            throw new \InvalidArgumentException('Helper resource requires a helper locator');
        }
        return new HelperResource($parts, $locator);
    }
}