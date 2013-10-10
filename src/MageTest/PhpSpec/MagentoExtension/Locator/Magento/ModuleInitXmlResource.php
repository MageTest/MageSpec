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

use PhpSpec\Locator\ResourceInterface;

/**
 * ModelResource
 *
 * @category   MageTest
 * @package    PhpSpec_MagentoExtension
 *
 * @author     MageTest team (https://github.com/MageTest/MageSpec/contributors)
 */
class ModuleInitXmlResource implements ResourceInterface
{
    private $parts;
    private $locator;

    public function __construct(array $parts, ModuleInitXmlLocator $locator)
    {
        $this->parts   = array_slice($parts, 0, 2);
        $this->locator = $locator;
    }

    public function getName()
    {
        return implode('_', $this->parts);
    }

    public function getSpecName()
    {
        return '';
    }

    public function getSrcFilename()
    {
        return $this->locator->getFullSrcPath() . $this->getName() . '.xml';
    }

    public function getSrcNamespace()
    {
        return '';
    }

    public function getSrcClassname()
    {
        return '';
    }

    public function getSpecFilename()
    {
        return '';
    }

    public function getSpecNamespace()
    {
        return '';
    }

    public function getSpecClassname()
    {
        return '';
    }
}
