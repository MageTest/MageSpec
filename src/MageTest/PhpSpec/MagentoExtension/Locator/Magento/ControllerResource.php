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
class ControllerResource implements ResourceInterface
{
    private $parts;
    private $pathParts;
    private $locator;

    public function __construct(array $parts, ControllerLocator $locator)
    {
        $this->parts   = $parts;
        $this->pathParts = $parts;
        array_splice($this->pathParts, 2, 0, array('controllers'));
        $this->locator = $locator;
    }

    public function getName()
    {
        return implode('_', $this->parts);
    }

    public function getSpecName()
    {
        return $this->getName() . 'Spec';
    }

    public function getSrcFilename()
    {
        return $this->locator->getFullSrcPath() . implode(DIRECTORY_SEPARATOR, $this->pathParts) . '.php';
    }

    public function getSrcNamespace()
    {
        return '';
    }

    public function getSrcClassname()
    {
        return implode('_', $this->parts);
    }

    public function getSpecFilename()
    {
        return $this->locator->getFullSpecPath() . implode(DIRECTORY_SEPARATOR, $this->pathParts) . 'Spec.php';
    }

    public function getSpecNamespace()
    {
        return rtrim($this->locator->getSpecNamespace(), '/\\');
    }

    public function getSpecClassname()
    {
        return $this->locator->getSpecNamespace() . implode('_', $this->parts).'Spec';
    }
}