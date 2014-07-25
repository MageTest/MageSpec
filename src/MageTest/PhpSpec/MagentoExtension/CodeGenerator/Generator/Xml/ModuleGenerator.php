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
        $this->fileSystem->putFileContents(
            $this->getFilePath($moduleName),
            strtr(file_get_contents(__FILE__, null, null, __COMPILER_HALT_OFFSET__), $values)
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
__halt_compiler();<?xml version="1.0" encoding="UTF-8"?>
<config>
    <modules>
        <%module_name%>
            <active>true</active>
            <codePool>%code_pool%</codePool>
        </%module_name%>
    </modules>
</config>