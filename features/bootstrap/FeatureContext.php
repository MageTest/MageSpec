<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\SnippetAcceptingContext;
use Symfony\Component\Console\Input\ArgvInput;

/**
 * Behat context class.
 */
class FeatureContext implements SnippetAcceptingContext
{
    private $configFile;
    private $namespace;

    /**
     * Initializes context.
     *
     * Every scenario gets it's own context object.
     * You can also pass arbitrary arguments to the context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given Magespec has a standard configuration
     */
    public function magespecHasAStandardConfiguration()
    {
        $this->configFile = __DIR__ . '/config_files/standard.yml';
    }

    /**
     * @Given my module namespace is :namespace
     */
    public function myModuleNamespaceIs($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @When I describe a :objectType
     */
    public function iDescribeA($objectType)
    {
        $app = new PhpSpec\Console\Application('version');
        $app->setAutoExit(false);
        $app->run(
            new ArgvInput(array(
                'phpspec',
                'describe:' . $objectType,
                '-q',
                '--config',
                $this->configFile,
                strtolower($this->namespace) . '/test'
            ))
        );
    }

    /**
     * @Then a correctly namespaced block spec should be generated
     */
    public function aCorrectlyNamespacedBlockSpecShouldBeGenerated()
    {
        if (!file_exists('spec/public/app/code/local/Behat/Test/Block/TestSpec.php')) {
            throw new \RuntimeException('Block spec not found in spec/public/app/code/local/Behat/Test/Block/');
        }
        require('spec/public/app/code/local/Behat/Test/Block/TestSpec.php');
        if (!class_exists('spec\Behat_Test_Block_TestSpec', false)) {
            throw new \RuntimeException('Class Behat_Test_Block_TestSpec not found');
        }
    }

    /**
     * @Then a correctly namespaced controller spec should be generated
     */
    public function aCorrectlyNamespacedControllerSpecShouldBeGenerated()
    {
        if (!file_exists('spec/public/app/code/local/Behat/Test/controllers/TestControllerSpec.php')) {
            throw new \RuntimeException(
                'Controller spec not found in spec/public/app/code/local/Behat/Test/controllers/'
            );
        }
        require('spec/public/app/code/local/Behat/Test/controllers/TestControllerSpec.php');
        if (!class_exists('spec\Behat_Test_TestControllerSpec', false)) {
            throw new \RuntimeException('Class Behat_Test_TestControllerSpec not found');
        }
    }

    /**
     * @Then a correctly namespaced helper spec should be generated
     */
    public function aCorrectlyNamespacedHelperSpecShouldBeGenerated()
    {
        if (!file_exists('spec/public/app/code/local/Behat/Test/Helper/TestSpec.php')) {
            throw new \RuntimeException('Helper spec not found in spec/public/app/code/local/Behat/Test/Helper/');
        }
        require('spec/public/app/code/local/Behat/Test/Helper/TestSpec.php');
        if (!class_exists('spec\Behat_Test_Helper_TestSpec', false)) {
            throw new \RuntimeException('Class Behat_Test_Helper_TestSpec not found');
        }
    }

    /**
     * @Then a correctly namespaced model spec should be generated
     */
    public function aCorrectlyNamespacedModelSpecShouldBeGenerated()
    {
        if (!file_exists('spec/public/app/code/local/Behat/Test/Model/TestSpec.php')) {
            throw new \RuntimeException('Model spec not found in spec/public/app/code/local/Behat/Test/Model/');
        }
        require('spec/public/app/code/local/Behat/Test/Model/TestSpec.php');
        if (!class_exists('spec\Behat_Test_Model_TestSpec', false)) {
            throw new \RuntimeException('Class Behat_Test_Model_TestSpec not found');
        }
    }

    /**
     * @Then a correctly namespaced resource model spec should be generated
     */
    public function aCorrectlyNamespacedResourceModelSpecShouldBeGenerated()
    {
        if (!file_exists('spec/public/app/code/local/Behat/Test/Model/Resource/TestSpec.php')) {
            throw new \RuntimeException('Model spec not found in spec/public/app/code/local/Behat/Test/Model/Resource');
        }
        require('spec/public/app/code/local/Behat/Test/Model/Resource/TestSpec.php');
        if (!class_exists('spec\Behat_Test_Model_Resource_TestSpec', false)) {
            throw new \RuntimeException('Class Behat_Test_Model_Resource_TestSpec not found');
        }
    }

    /**
     * @AfterScenario
     */
    public function afterScenario()
    {
        $this->recursiveRemoveDirectory('spec/public');
    }

    private function recursiveRemoveDirectory($directory)
    {
        foreach(glob("{$directory}/*") as $file)
        {
            if(is_dir($file)) {
                $this->recursiveRemoveDirectory($file);
            } else {
                unlink($file);
            }
        }
        rmdir($directory);
    }
}
