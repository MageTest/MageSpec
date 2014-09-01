<?php

namespace OutputSpecification;


interface ObjectSpecification
{
    /**
     * @return string
     */
    public function getClassName();

    /**
     * @return string
     */
    public function getFilePath();

    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function getDirectory();
} 