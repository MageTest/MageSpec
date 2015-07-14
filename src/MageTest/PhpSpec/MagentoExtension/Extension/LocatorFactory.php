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
namespace MageTest\PhpSpec\MagentoExtension\Extension;


use MageTest\PhpSpec\MagentoExtension\Locator\Magento\AbstractResourceLocator;
use PhpSpec\Util\Filesystem;

class LocatorFactory
{
    /**
     * @var string
     */
    private $sourceNamespace;

    /**
     * @var string
     */
    private $specPrefix;

    /**
     * @var string
     */
    private $sourcePath;

    /**
     * @var string
     */
    private $specPath;

    /**
     * @var string
     */
    private $codePool;

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(
        $srcNamespace = '',
        $specNamespacePrefix = '',
        $srcPath = 'src',
        $specPath = 'spec',
        Filesystem $filesystem = null,
        $codePool = null
    ) {
        $this->sourceNamespace = $srcNamespace;
        $this->specPrefix = $specNamespacePrefix;
        $this->sourcePath = $srcPath;
        $this->specPath = $specPath;
        $this->filesystem = $filesystem;
        $this->codePool = $codePool;
    }

    /**
     * @param string $type
     * @return AbstractResourceLocator
     */
    public function getLocator($type)
    {
        $className = '\\MageTest\\PhpSpec\\MagentoExtension\\Locator\\Magento\\' . ucfirst($type) . 'Locator';

        if (!class_exists($className)) {
            throw new \RuntimeException("The locator $className does not exist");
        }

        return new $className(
            $this->sourceNamespace,
            $this->specPrefix,
            $this->sourcePath,
            $this->specPath,
            $this->filesystem,
            $this->codePool
        );
    }
}