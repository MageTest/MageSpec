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

use InvalidArgumentException;
use PhpSpec\Locator\ResourceLocatorInterface;
use PhpSpec\Util\Filesystem;

/**
 * ControllerLocator
 *
 * @category   MageTest
 * @package    PhpSpec_MagentoExtension
 *
 * @author     MageTest team (https://github.com/MageTest/MageSpec/contributors)
 */
class ControllerLocator extends AbstractResourceLocator implements ResourceLocatorInterface
{
    protected $classType = 'controllers';

    protected $validator = '/^(controller):([a-zA-Z0-9]+)_([a-zA-Z0-9]+)\/([a-zA-Z0-9]+)$/';

    /**
     * @param string $classname
     * @return bool
     */
    public function supportsClass($classname)
    {
        return ($this->supportsQuery($classname) || preg_match('/Controller$/', $classname));
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return 10;
    }

    /**
     * @param string $file
     * @return bool
     */
    protected function isSupported($file)
    {
        return strpos($file, 'controllers') > 0;
    }

    /**
     * @param array $parts
     * @param ResourceLocatorInterface $locator
     * @return ControllerResource
     * @throws \InvalidArgumentException
     */
    protected function getResource(array $parts, ResourceLocatorInterface $locator)
    {
        if (!$locator instanceof ControllerLocator) {
            throw new \InvalidArgumentException('Controller resource requires a controller locator');
        }
        return new ControllerResource($parts, $locator);
    }

    /**
     * @param array $matches
     * @return string
     */
    protected function getClassnameFromMatches(array $matches)
    {
        $vendor = ucfirst(array_shift($matches));
        $module = ucfirst(array_shift($matches));

        $controller = implode('_', array_map('ucfirst', explode('_', implode($matches)))).'Controller';
        return implode('_', array($vendor, $module, $controller));
    }

    /**
     * @param string $path
     * @return string
     */
    protected function getRelative($path)
    {
        $relative = parent::getRelative($path);
        return str_replace(
            DIRECTORY_SEPARATOR . $this->classType . DIRECTORY_SEPARATOR,
            DIRECTORY_SEPARATOR,
            $relative
        );
    }
}