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
namespace MageTest\PhpSpec\MagentoExtension\CodeGenerator\Generator;

use MageTest\PhpSpec\MagentoExtension\Locator\Magento\HelperResource;
use PhpSpec\CodeGenerator\Generator\Generator as GeneratorInterface;
use PhpSpec\Locator\Resource as ResourceInterface;
/**
 * HelperGenerator
 *
 * @category   MageTest
 * @package    PhpSpec_MagentoExtension
 *
 * @author     MageTest team (https://github.com/MageTest/MageSpec/contributors)
 */
class HelperGenerator extends MagentoObjectGenerator implements GeneratorInterface
{
    /**
     * @param ResourceInterface $resource
     * @param string $generation
     * @param array $data
     * @return bool
     */
    public function supports(ResourceInterface $resource, $generation, array $data)
    {
        return 'class' === $generation && $resource instanceof HelperResource;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return 20;
    }

    /**
     * @param ResourceInterface $resource
     *
     * @return string
     */
    protected function getFilePath(ResourceInterface $resource)
    {
        return $resource->getSrcFilename();
    }

    /**
     * @param ResourceInterface $resource
     * @param string $filepath
     *
     * @return string
     */
    protected function getGeneratedMessage(ResourceInterface $resource, $filepath)
    {
        return sprintf(
            "<info>Magento helper <value>%s</value> created in <value>'%s'</value>.</info>\n",
            $resource->getSrcClassname(),
            $filepath
        );
    }

    /**
     * @return string
     */
    protected function getParentClass()
    {
        return 'Mage_Core_Helper_Abstract';
    }

    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return 'mage_helper';
    }
}
