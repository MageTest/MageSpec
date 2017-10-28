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

use PhpSpec\Locator\Resource as ResourceInterface;
use PhpSpec\Locator\ResourceLocator as ResourceLocatorInterface;

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
    public function supportsClass(string $classname): bool
    {
        return ($this->supportsQuery($classname) || preg_match('/Controller$/', $classname));
    }

    public function getPriority(): int
    {
        return 10;
    }

    protected function isSupported(string $file): bool
    {
        return strpos($file, 'controllers') > 0;
    }

    protected function getResource(array $parts, ResourceLocatorInterface $locator): ResourceInterface
    {
        if (!$locator instanceof ControllerLocator) {
            throw new \InvalidArgumentException('Controller resource requires a controller locator');
        }
        return new ControllerResource($parts, $locator);
    }

    protected function getObjectName(array $matches): string
    {
        return implode('_', array_map('ucfirst', explode('_', implode($matches)))).'Controller';
    }

    protected function getRelative(string $path): string
    {
        $relative = parent::getRelative($path);
        return str_replace(
            DIRECTORY_SEPARATOR . $this->getClassType() . DIRECTORY_SEPARATOR,
            DIRECTORY_SEPARATOR,
            $relative
        );
    }

    protected function getClassType(): string
    {
        return 'controllers';
    }

    protected function getValidator(): string
    {
        return '/^(controller):([a-zA-Z0-9]+)_([a-zA-Z0-9]+)\/([a-zA-Z0-9]+)$/';
    }
}
