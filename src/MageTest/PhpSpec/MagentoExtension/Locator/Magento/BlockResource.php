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

/**
 * BlockResource
 *
 * @category   MageTest
 * @package    PhpSpec_MagentoExtension
 *
 * @author     MageTest team (https://github.com/MageTest/MageSpec/contributors)
 */
class BlockResource implements ResourceInterface
{
    private $parts;
    private $locator;

    public function __construct(array $parts, BlockLocator $locator)
    {
        $this->parts   = $parts;
        $this->locator = $locator;
    }

    public function getName(): string
    {
        return implode('_', $this->parts);
    }

    public function getSpecName(): string
    {
        return $this->getName() . 'Spec';
    }

    public function getSrcFilename(): string
    {
        return $this->locator->getFullSrcPath() . implode(DIRECTORY_SEPARATOR, $this->parts) . '.php';
    }

    public function getSrcNamespace(): string
    {
        return '';
    }

    public function getSrcClassname(): string
    {
        return implode('_', $this->parts);
    }

    public function getSpecFilename(): string
    {
        return $this->locator->getFullSpecPath() . implode(DIRECTORY_SEPARATOR, $this->parts) . 'Spec.php';
    }

    public function getSpecNamespace(): string
    {
        return rtrim($this->locator->getSpecNamespace(), '/\\');
    }

    public function getSpecClassname(): string
    {
        return $this->locator->getSpecNamespace() . implode('_', $this->parts).'Spec';
    }
}
