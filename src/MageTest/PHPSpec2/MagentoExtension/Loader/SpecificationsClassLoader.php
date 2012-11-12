<?php

namespace MageTest\PHPSpec2\MagentoExtension\Loader;

use MageTest\PHPSpec2\MagentoExtension\Loader\LoaderException;

use PHPSpec2\Loader\Node\Specification as NodeSpecification;

use ReflectionClass;
use ReflectionException;

class SpecificationsClassLoader
{
    public function loadFromfile($filename)
    {
        $specifications = array();

        if (preg_match("/^spec\/([a-zA-Z0-9]+)\/([a-zA-Z0-9]+)\/(controllers)\/(.*)/", $filename, $matches)) {
            $specifications = $this->loadControllerSpec($matches);
        } elseif (preg_match("/^spec\/([a-zA-Z0-9]+)\/([a-zA-Z0-9]+)\/(Block|Helper|Model)\/(.*)/", $filename, $matches)) {
            $specifications = $this->loadStandardMagentoSpec($matches);
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
        } catch (ReflectionException $e) {
            throw new LoaderException("Could not find $className in $filename");
        }
        return array(new NodeSpecification($className, $class));
    }
}
