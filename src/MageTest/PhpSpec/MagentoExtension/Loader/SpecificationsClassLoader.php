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
 * @subpackage Loader
 *
 * @copyright  Copyright (c) 2012-2013 MageTest team and contributors.
 */
namespace MageTest\PhpSpec\MagentoExtension\Loader;

use MageTest\PhpSpec\MagentoExtension\Loader\LoaderException;

use PhpSpec\Loader\Node\Specification as NodeSpecification;

use ReflectionClass;
use ReflectionException;

/**
 * SpecificationsClassLoader
 *
 * @category   MageTest
 * @package    PhpSpec_MagentoExtension
 * @subpackage Loader
 *
 * @author     MageTest team (https://github.com/MageTest/MageSpec/contributors)
 */
class SpecificationsClassLoader //implements \PhpSpec\Loader\LoaderInterface
{
    public function loadFromfile($filename, $line = null)
    {
        $specifications = array();

        if (preg_match("/^spec\/([a-zA-Z0-9]+)\/([a-zA-Z0-9]+)\/(controllers)\/(.*)/", $filename, $matches)) {
            $specifications = $this->loadControllerSpec($matches);
        } elseif (preg_match("/^spec\/([a-zA-Z0-9]+)\/([a-zA-Z0-9]+)\/(Block|Helper|Model)\/(.*)/", $filename, $matches)) {
            $specifications = $this->loadStandardMagentoSpec($matches);
        } elseif (preg_match("/^spec\/([a-zA-Z0-9]+)\/([a-zA-Z0-9]+)\/(sql)\/(.*)/", $filename, $matches)) {
            $specifications = $this->loadSetupScriptsSpec($matches);
        }

        return $specifications;
    }

    private function loadControllerSpec($matches)
    {
        $filename = $matches[0];
        $vendor = $matches[1];
        $module = $matches[2];
        $controllersFolder = $matches[3];
        $controllerFile = $matches[4];
        $controllerClass = str_replace(".php", "", $controllerFile);
        $controllerClass = str_replace("/", "_", $controllerClass);
        $controllerClass = "spec\\{$vendor}_{$module}_{$controllerClass}";

        require_once $filename;
        try {
            $class = new ReflectionClass($controllerClass);
        } catch (ReflectionException $e) {
            throw new LoaderException("Could not find $controllerClass in $filename");
        }
        return array(new NodeSpecification($controllerClass, $class));
    }

    private function loadStandardMagentoSpec($matches)
    {
        $filename = $matches[0];
        $vendor = $matches[1];
        $module = $matches[2];
        $folder = $matches[3];
        $path = $matches[4];
        $className = str_replace(".php", "", $path);
        $className = str_replace("/", "_", $className);
        $className = "spec\\{$vendor}_{$module}_{$folder}_{$className}";

        require_once $filename;
        try {
            $class = new ReflectionClass($className);
            $this->validateSpecificationSuperClass($folder, $class);
        } catch (ReflectionException $e) {
            throw new LoaderException("Could not find $className in $filename");
        }
        return array(new NodeSpecification($className, $class));
    }

    private function validateSpecificationSuperClass($type, $class)
    {
        switch ($type) {
            case 'Block':
                if (!$class->isSubclassOf('MageTest\PhpSpec\MagentoExtension\Specification\BlockBehavior')) {
                    throw new LoaderException($class->getName() . " is not a BlockBehavior");
                }
                break;
            case 'Helper':
                if (!$class->isSubclassOf('MageTest\PhpSpec\MagentoExtension\Specification\HelperBehavior')) {
                    throw new LoaderException($class->getName() . " is not a HelperBehavior");
                }
                break;
            default:

        }
    }

    private function loadSetupScriptsSpec($matches)
    {
        $vendor = $matches[1];
        $module = $matches[2];
        $path = "spec/{$vendor}/{$module}/sql";
        $setupSpec = "{$vendor}_{$module}_Setup";

        if (class_exists($setupSpec)) {
            return array();
        }

        $factory = new SetupScriptNodeSpecificationFactory($setupSpec, $path);
        return array($factory->create());
    }
}
