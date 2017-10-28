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
 * BlockLocator
 *
 * @category   MageTest
 * @package    PhpSpec_MagentoExtension
 *
 * @author     MageTest team (https://github.com/MageTest/MageSpec/contributors)
 */
class BlockLocator extends AbstractResourceLocator implements ResourceLocatorInterface
{

    public function getPriority(): int
    {
        return 30;
    }

    protected function isSupported(string $file): bool
    {
        return strpos($file, 'Block') > 0;
    }

    protected function getResource(array $parts, ResourceLocatorInterface $locator): ResourceInterface
    {
        if (!$locator instanceof BlockLocator) {
            throw new \InvalidArgumentException('Block resource requires a block locator');
        }
        return new BlockResource($parts, $locator);
    }

    protected function getClassType(): string
    {
        return 'Block';
    }

    protected function getValidator(): string
    {
        return '/^(block):([a-zA-Z0-9]+)_([a-zA-Z0-9]+)\/([a-zA-Z0-9]+)(_[\w]+)?$/';
    }
}
