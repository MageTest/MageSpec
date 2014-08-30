<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\SnippetAcceptingContext;
use PhpSpec\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Filesystem\Filesystem;
use Console\ApplicationTester;

/**
 * Behat context class.
 */
class FeatureContext implements SnippetAcceptingContext
{
    private $configFile;
    private $namespace;
    private $applicationTester;
    private $filesystem;
    private $currentSpec;

    public function __construct()
    {
        $this->filesystem = new Filesystem();
        $this->removeTemporaryDirectories();
    }

    /**
     * @AfterScenario
     */
    public function removeTemporaryDirectories()
    {
        $this->filesystem->remove('spec/public');
        $this->filesystem->remove('public');
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
        $this->applicationTester = $this->createApplicationTester();
        $this->applicationTester->run(
            sprintf(
                'describe:%s --no-interaction --config %s %s',
                $objectType,
                $this->configFile,
                strtolower($this->namespace) . '/test'
            ),
            array('decorated' => false)
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
     * @Given that there is a :objectType spec
     */
    public function thatThereIsASpec($objectType)
    {
        switch ($objectType) {
            case 'controller':
                $dir = 'controllers';
                $filename = 'TestControllerSpec';
                break;
            case 'resource model':
                $dir = 'Model/Resource';
                $filename = 'TestSpec';
                $objectType = str_replace(' ', '_', $objectType);
                break;
            default:
                $dir = ucfirst($objectType);
                $filename = 'TestSpec';

        }
        $template = __DIR__ . "/templates/specs/$objectType.template";
        $this->currentSpec = "spec/public/app/code/local/Behat/Spec/$dir/$filename.php";
        $this->filesystem->copy($template, $this->currentSpec);
    }

    /**
     * @When Magespec runs the spec
     */
    public function magespecRunsTheSpec()
    {
        $this->applicationTester = $this->createApplicationTester();
        $this->applicationTester->putToInputStream("y\n");
        $this->applicationTester->run(
            sprintf('run --config %s %s', $this->configFile, $this->currentSpec),
            array('interactive' => true, 'decorated' => false)
        );
    }

    /**
     * @Then a block class should be generated
     */
    public function aBlockClassShouldBeGenerated()
    {
        if (!file_exists('public/app/code/local/Behat/Spec/Block/Test.php')) {
            throw new \RuntimeException('Block class not found in public/app/code/local/Behat/Spec/Block');
        }
        if (!class_exists('Behat_Spec_Block_Test', false)) {
            throw new \RuntimeException('Class Behat_Spec_Block_Test not found');
        }
    }

    /**
     * @Then a controller class should be generated
     */
    public function aControllerClassShouldBeGenerated()
    {
        if (!file_exists('public/app/code/local/Behat/Spec/controllers/TestController.php')) {
            throw new \RuntimeException('Controller class not found in public/app/code/local/Behat/Spec/controllers');
        }
        if (!class_exists('Behat_Spec_TestController', false)) {
            throw new \RuntimeException('Class Behat_Spec_TestController not found');
        }
    }

    /**
     * @Then a helper class should be generated
     */
    public function aHelperClassShouldBeGenerated()
    {
        if (!file_exists('public/app/code/local/Behat/Spec/Helper/Test.php')) {
            throw new \RuntimeException('Helper class not found in public/app/code/local/Behat/Spec/Helper');
        }
        if (!class_exists('Behat_Spec_Helper_Test', false)) {
            throw new \RuntimeException('Class Behat_Spec_Helper_Test not found');
        }
    }

    /**
     * @Then a model class should be generated
     */
    public function aModelClassShouldBeGenerated()
    {
        if (!file_exists('public/app/code/local/Behat/Spec/Model/Test.php')) {
            throw new \RuntimeException('Model class not found in public/app/code/local/Behat/Spec/Model');
        }
        if (!class_exists('Behat_Spec_Model_Test', false)) {
            throw new \RuntimeException('Class Behat_Spec_Model_Test not found');
        }
    }

    /**
     * @Then a resource model class should be generated
     */
    public function aResourceModelClassShouldBeGenerated()
    {
        if (!file_exists('public/app/code/local/Behat/Spec/Model/Resource/Test.php')) {
            throw new \RuntimeException(
                'Resource model class not found in public/app/code/local/Behat/Spec/Model/Resource'
            );
        }
        if (!class_exists('Behat_Spec_Model_Resource_Test', false)) {
            throw new \RuntimeException('Class Behat_Spec_Model_Resource_Test not found');
        }
    }

    /**
     * @return ApplicationTester
     */
    private function createApplicationTester()
    {
        $application = new Application('version');
        $application->setAutoExit(false);

        return new ApplicationTester($application);
    }
}
