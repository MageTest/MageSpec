<?php

namespace MageTest\PHPSpec2\MagentoExtension\Loader;

use PHPSpec2\Loader\Node\Specification as NodeSpecification;

use Symfony\Component\Finder\Finder;

use ReflectionClass;

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

use MageTest\PHPSpec2\MagentoExtension\Specification\SetupScriptBehavior;

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