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
use PhpSpec\Locator\ResourceLocator;

/**
 * ModelLocator
 *
 * @category   MageTest
 * @package    PhpSpec_MagentoExtension
 *
 * @author     MageTest team (https://github.com/MageTest/MageSpec/contributors)
 */
class ModelLocator extends AbstractResourceLocator implements ResourceLocator
{
    /**
     * @return int
     */
    public function getPriority(): int
    {
        return 40;
    }

    protected function isSupported(string $file): bool
    {
        return strpos($file, 'Model') > 0;
    }

    protected function getResource(array $parts, ResourceLocator $locator): ResourceInterface
    {
        if (!$locator instanceof ModelLocator) {
            throw new \InvalidArgumentException('Model resource requires a model locator');
        }
        return new ModelResource($parts, $locator);
    }

    protected function getClassType(): string
    {
        return 'Model';
    }

    protected function getValidator(): string
    {
        return '/^(model):([a-zA-Z0-9]+)_([a-zA-Z0-9]+)\/([a-zA-Z0-9]+)(_[\w]+)?$/';
    }
}
