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
namespace MageTest\PhpSpec\MagentoExtension\Configuration;

class MageLocator
{
    const DEFAULT_SRC_PATH = 'src';
    const DEFAULT_SPEC_PATH = 'spec';

    private $configuration;

    private function __construct(array $params)
    {
        $this->configuration = isset($params['mage_locator']) ? $params['mage_locator'] : [];
    }

    public static function fromParams(array $params = [])
    {
        return new MageLocator($params);
    }

    public function getNamespace()
    {
        return array_key_exists('namespace', $this->configuration) ? $this->configuration['namespace'] : '';
    }

    public function getSpecPrefix()
    {
        return array_key_exists('spec_prefix', $this->configuration) ?
            $this->configuration['spec_prefix'] :
            self::DEFAULT_SPEC_PATH;
    }

    public function getSrcPath()
    {
        return array_key_exists('src_path', $this->configuration) ?
            rtrim($this->configuration['src_path'], DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR :
            self::DEFAULT_SRC_PATH;
    }

    public function getSpecPath()
    {
        return array_key_exists('spec_path', $this->configuration) ?
            rtrim($this->configuration['spec_path'], DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR :
            '.';
    }

    public function getCodePool()
    {
        return array_key_exists('code_pool', $this->configuration) ? $this->configuration['code_pool'] : 'local';
    }
}
