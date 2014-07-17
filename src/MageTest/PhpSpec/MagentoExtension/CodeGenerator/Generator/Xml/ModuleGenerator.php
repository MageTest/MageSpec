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

    public function __construct($path, Filesystem $fileSystem = null)
    {
        $this->fileSystem = $fileSystem ?: new Filesystem;
        $this->path = $path;
    }

    public function generate($moduleName)
    {
        if ($this->moduleFileExists($moduleName)) {
            return;
        }

        $values = array(
            '%module_name%' => $moduleName
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
            <codePool>local</codePool>
        </%module_name%>
    </modules>
</config>