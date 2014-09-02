<?php

namespace OutputSpecification;


class ClassSpecification implements ObjectSpecification
{
    private $type;
    private $filePath;
    private $className;

    public function __construct($type, $filePath, $className)
    {
        $this->type = $type;
        $this->filePath = $filePath;
        $this->className = $className;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getDirectory()
    {
        return dirname($this->filePath);
    }
} 