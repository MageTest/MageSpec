<?php

namespace MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator\Xml;

use PhpSpec\Util\Filesystem;

class ModuleGenerator
{
    /**
     * @var Filesystem
     */
    private $fileSystem;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $codePool;

    /**
     * @param string $path
     */
    public function __construct($path, Filesystem $fileSystem, $codePool = 'local')
    {
        $this->fileSystem = $fileSystem;
        $this->path = $path;
        $this->codePool = $codePool;
    }

    public function generate($moduleName)
    {
        if ($this->moduleFileExists($moduleName)) {
            return;
        }

        $values = array(
            '%module_name%' => $moduleName,
            '%code_pool%' => $this->codePool,
        );

        if (!$this->fileSystem->pathExists($this->path)) {
            $this->fileSystem->makeDirectory($this->path);
        }

        $this->fileSystem->putFileContents(
            $this->getFilePath($moduleName),
            strtr(file_get_contents(__DIR__ . '/templates/module.template'), $values)
        );
    }

    private function getFilePath($moduleName)
    {
        return $this->path . $moduleName . '.xml';
    }

    private function moduleFileExists($moduleName)
    {
        return $this->fileSystem->pathExists($this->getFilePath($moduleName));
    }
}
