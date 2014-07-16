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

    public function __construct(Filesystem $fileSystem, $path)
    {
        $this->fileSystem = $fileSystem;
        $this->path = $path;
    }

    public function moduleFileExists($moduleName)
    {
        return $this->fileSystem->pathExists($this->getFilePath($moduleName));
    }

    public function generate($moduleName)
    {
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