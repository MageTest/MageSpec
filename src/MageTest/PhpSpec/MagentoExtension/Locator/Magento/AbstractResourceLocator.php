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
use PhpSpec\Util\Filesystem;

abstract class AbstractResourceLocator
{
    protected $classType;
    protected $validator;
    protected $srcPath;
    protected $specPath;
    protected $srcNamespace;
    protected $specNamespace;
    protected $fullSrcPath;
    protected $fullSpecPath;
    protected $filesystem;
    protected $codePool;

    public function __construct(
        $srcNamespace = '',
        $specNamespacePrefix = '',
        $srcPath = 'src',
        $specPath = 'spec',
        Filesystem $filesystem = null,
        $codePool = null
    ) {
        $this->checkInitialData();

        $this->setFilesystem($filesystem);
        $this->setCodePool($codePool);

        $this->srcPath       = rtrim(realpath($srcPath), '/\\') . DIRECTORY_SEPARATOR . $this->codePool . DIRECTORY_SEPARATOR;
        $this->specPath      = rtrim(realpath($specPath), '/\\') . DIRECTORY_SEPARATOR . $this->codePool . DIRECTORY_SEPARATOR;
        $this->srcNamespace  = ltrim(trim($srcNamespace, ' \\') . '\\', '\\');
        $this->specNamespace = trim($specNamespacePrefix, ' \\') . '\\';
        $this->fullSrcPath   = $this->srcPath;
        $this->fullSpecPath  = $this->specPath;

        $this->validatePaths($srcPath, $specPath);
    }

    public function getFullSrcPath()
    {
        return $this->fullSrcPath;
    }

    public function getFullSpecPath()
    {
        return $this->fullSpecPath;
    }

    public function getSrcNamespace()
    {
        return $this->srcNamespace;
    }

    public function getSpecNamespace()
    {
        return $this->specNamespace;
    }

    public function getCodePool()
    {
        return $this->codePool;
    }

    public function getAllResources()
    {
        return $this->findSpecResources($this->fullSpecPath);
    }

    public function supportsQuery($query)
    {
        $isSupported = (bool) preg_match($this->validator, $query) || $this->isSupported($query);;

        return $isSupported;
    }

    public function findResources($query)
    {
        $path = $this->getCleanPath($query);

        foreach (array($this->fullSrcPath, $this->srcPath) as $srcPath) {
            if (0 === strpos($path, $srcPath)) {
                $path = $srcPath.substr($path, strlen($srcPath));
                $path = preg_replace('/\.php/', 'Spec.php', $path);

                return $this->findSpecResources($path);
            }
        }

        if (0 === strpos($path, $this->specPath)) {
            return $this->findSpecResources($path);
        }

        return array();
    }

    public function supportsClass($classname)
    {
        $parts = explode('_', $classname);

        if (count($parts) < 2) {
            return false;
        }

        return (
            $this->supportsQuery($classname) ||
            $classname === implode('_', array($parts[0], $parts[1], $this->classType, $parts[count($parts)-1]))
        );
    }

    public function createResource($classname)
    {
        preg_match($this->validator, $classname, $matches);

        if (!empty($matches)) {
            array_shift($matches);
            array_shift($matches);

            $classname = $this->getClassnameFromMatches($matches);
        }

        return $this->getResource(explode('_', $classname), $this);
    }

    abstract public function getPriority();

    /**
     * @param string $path
     */
    protected function findSpecResources($path)
    {
        if (!$this->filesystem->pathExists($path)) {
            return array();
        }

        if ('.php' === substr($path, -4)) {
            if (!$this->isSupported($path)) {
                return array();
            }

            return array($this->createResourceFromSpecFile(realpath($path)));
        }

        $resources = array();
        foreach ($this->filesystem->findSpecFilesIn($path) as $file) {
            $specFile = $file->getRealPath();
            if ($this->isSupported($specFile)) {
                $resources[] = $this->createResourceFromSpecFile($specFile);
            }
        }

        return $resources;
    }

    private function createResourceFromSpecFile($path)
    {
        // cut "Spec.php" from the end
        $relative = $this->getRelative($path);

        return $this->getResource(explode(DIRECTORY_SEPARATOR, $relative), $this);
    }

    private function checkInitialData()
    {
        if (null === $this->classType) {
            throw new \UnexpectedValueException('Concrete resource locators mist specify a class type');
        }

        if (null === $this->validator) {
            throw new \UnexpectedValueException('Concrete resource locators mist specify a validation rule');
        }
    }

    private function setFilesystem($filesystem)
    {
        $this->filesystem = $filesystem ? : new Filesystem;
    }

    private function setCodePool($codePool)
    {
        $this->codePool = $codePool ? : 'local';
    }

    private function validatePaths($srcPath, $specPath)
    {
        $invalidPath = DIRECTORY_SEPARATOR . $this->codePool . DIRECTORY_SEPARATOR;

        if ($invalidPath === $this->srcPath) {
            throw new \InvalidArgumentException(sprintf(
                'Source code path should be existing filesystem path, but "%s" given.',
                $srcPath
            ));
        }

        if ($invalidPath === $this->specPath) {
            throw new \InvalidArgumentException(sprintf(
                'Specs code path should be existing filesystem path, but "%s" given.',
                $specPath
            ));
        }
    }

    private function getCleanPath($query)
    {
        $path = rtrim(realpath(str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, $query)), DIRECTORY_SEPARATOR);

        if ('.php' !== substr($path, -4)) {
            $path .= DIRECTORY_SEPARATOR;
        }

        return $path;
    }

    private function getClassnameFromMatches(array $matches)
    {
        $vendor = ucfirst(array_shift($matches));
        $module = ucfirst(array_shift($matches));

        return implode('_', array($vendor, $module, $this->getObjectName($matches)));
    }

    protected function getObjectName(array $matches)
    {
        return $this->classType . '_' . implode('_', array_map('ucfirst', explode('_', implode($matches))));
    }

    protected function getRelative($path)
    {
        // cut "Spec.php" from the end
        $relative = substr($path, strlen($this->fullSpecPath), -4);
        return preg_replace('/Spec$/', '', $relative);
    }

    abstract protected function isSupported($file);

    abstract protected function getResource(array $parts, ResourceLocatorInterface $locator);
}
