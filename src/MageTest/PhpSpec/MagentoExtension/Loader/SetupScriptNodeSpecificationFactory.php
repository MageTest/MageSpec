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
 * @subpackage Loader
 *
 * @copyright  Copyright (c) 2012-2013 MageTest team and contributors.
 */
namespace MageTest\PhpSpec\MagentoExtension\Loader;

use PhpSpec\Loader\Node\Specification as NodeSpecification;

use Symfony\Component\Finder\Finder;

use ReflectionClass;

/**
 * SetupScriptNodeSpecificationFactory
 *
 * @category   MageTest
 * @package    PhpSpec_MagentoExtension
 * @subpackage Loader
 *
 * @author     MageTest team (https://github.com/MageTest/MageSpec/contributors)
 */
class SetupScriptNodeSpecificationFactory
{
	private $setupSpec;
	private $path;
	private $finder;

    public function __construct($setupSpec, $path, Finder $finder = null)
    {
        $this->setupSpec = $setupSpec;
        $this->path = $path;
        $this->finder = $finder ?: new Finder;
    }

	public function create()
	{
        $script = $this->getLatestUpdateScript();

        if (empty($script)) {
        	return;
        }

        $class = <<<CLAZZ

namespace spec;

use MageTest\PhpSpec\MagentoExtension\Specification\SetupScriptBehavior;

class $this->setupSpec extends SetupScriptBehavior
{
	public function run()
	{
		\$installer = \$this;
		include "{$script}";
	}
}

CLAZZ;
        eval($class);
        $className = "spec\\$this->setupSpec";
        return new NodeSpecification($className, new ReflectionClass($className));
	}

	private function getLatestUpdateScript()
	{
		$script = '';
        $files = $this->finder->files()->sortByName()->in($this->path);

        if (iterator_count($files) === 0) {
            throw new LoaderException("No install script in {$this->path}");
        }

        foreach ($files as $file) {
            if (iterator_count($files) === 1) {
            	return $file->getFilename();
            }
        }

        return $script;
	}
}