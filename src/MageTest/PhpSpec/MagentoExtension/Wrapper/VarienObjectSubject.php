<?php
/**
 * [application]
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Apache License, Version 2.0 that is
 * bundled with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to <${EMAIL}> so we can send you a copy immediately.
 *
 * @category   [category]
 * @package    [package]
 * @copyright  Copyright (c) 2012 debo <${EMAIL}> (${URL})
 */

/**
 * [name]
 *
 * @category   [category]
 * @package    [package]
 * @author     debo <${EMAIL}> (${URL})
 */

namespace MageTest\PhpSpec\MagentoExtension\Wrapper;

use PhpSpec\Wrapper\Subject;

class VarienObjectSubject extends Subject
{
    public function getWrappedObject()
    {
        $object = parent::getWrappedObject();
        if ($object instanceof VarienObjectProxy) {
            $object = $object->getVarienObject();
        }

        return $object;
    }
}