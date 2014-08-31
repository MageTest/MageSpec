<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\SnippetAcceptingContext;
use Console\ApplicationTester;
use OutputSpecification\ClassSpecification;
use OutputSpecification\ObjectSpecification;
use OutputSpecification\SpecSpecification;
use PhpSpec\Console\Application;
use Symfony\Component\Filesystem\Filesystem;


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
     * @BeforeScenario
     */
    public function createNewApplicationTester()
    {
        $this->applicationTester = $this->createApplicationTester();
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
        $this->checkSpecIsGenerated(new SpecSpecification(
            'Block',
            'spec/public/app/code/local/Behat/Test/Block/TestSpec.php',
            'Behat_Test_Block_TestSpec'
        ));
    }

    /**
     * @Then a correctly namespaced controller spec should be generated
     */
    public function aCorrectlyNamespacedControllerSpecShouldBeGenerated()
    {
        $this->checkSpecIsGenerated(new SpecSpecification(
            'Controller',
            'spec/public/app/code/local/Behat/Test/controllers/TestControllerSpec.php',
            'Behat_Test_TestControllerSpec'
        ));
    }

    /**
     * @Then a correctly namespaced helper spec should be generated
     */
    public function aCorrectlyNamespacedHelperSpecShouldBeGenerated()
    {
        $this->checkSpecIsGenerated(new SpecSpecification(
            'Helper',
            'spec/public/app/code/local/Behat/Test/Helper/TestSpec.php',
            'Behat_Test_Helper_TestSpec'
        ));
    }

    /**
     * @Then a correctly namespaced model spec should be generated
     */
    public function aCorrectlyNamespacedModelSpecShouldBeGenerated()
    {
        $this->checkSpecIsGenerated(new SpecSpecification(
            'Model',
            'spec/public/app/code/local/Behat/Test/Model/TestSpec.php',
            'Behat_Test_Model_TestSpec'
        ));
    }

    /**
     * @Then a correctly namespaced resource model spec should be generated
     */
    public function aCorrectlyNamespacedResourceModelSpecShouldBeGenerated()
    {
        $this->checkSpecIsGenerated(new SpecSpecification(
            'Resource model',
            'spec/public/app/code/local/Behat/Test/Model/Resource/TestSpec.php',
            'Behat_Test_Model_Resource_TestSpec'
        ));
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
        $this->checkClassIsGenerated(new ClassSpecification(
            'Block',
            'public/app/code/local/Behat/Spec/Block/Test.php',
            'Behat_Spec_Block_Test'
        ));
    }

    /**
     * @Then a controller class should be generated
     */
    public function aControllerClassShouldBeGenerated()
    {
        $this->checkClassIsGenerated(new ClassSpecification(
            'Controller',
            'public/app/code/local/Behat/Spec/controllers/TestController.php',
            'Behat_Spec_TestController'
        ));
    }

    /**
     * @Then a helper class should be generated
     */
    public function aHelperClassShouldBeGenerated()
    {
        $this->checkClassIsGenerated(new ClassSpecification(
            'Helper',
            'public/app/code/local/Behat/Spec/Helper/Test.php',
            'Behat_Spec_Helper_Test'
        ));
    }

    /**
     * @Then a model class should be generated
     */
    public function aModelClassShouldBeGenerated()
    {
        $this->checkClassIsGenerated(new ClassSpecification(
            'Model',
            'public/app/code/local/Behat/Spec/Model/Test.php',
            'Behat_Spec_Model_Test'
        ));
    }

    /**
     * @Then a resource model class should be generated
     */
    public function aResourceModelClassShouldBeGenerated()
    {
        $this->checkClassIsGenerated(new ClassSpecification(
            'Resource model',
            'public/app/code/local/Behat/Spec/Model/Resource/Test.php',
            'Behat_Spec_Model_Resource_Test'
        ));
    }

    /**
     * @Given that there is a spec for a module that does not yet exist
     */
    public function thatThereIsASpecForAModuleThatDoesNotYetExist()
    {
        $template = __DIR__ . "/templates/specs/unique_model.template";
        $this->currentSpec = "spec/public/app/code/local/Behat/Unique/Model/TestSpec.php";
        $this->filesystem->copy($template, $this->currentSpec);

        expect($this->filesystem->exists('public/app/code/local/Behat/Unique'))->toBe(false);
    }

    /**
     * @Then the module XML file should be generated
     */
    public function theModuleXmlFileShouldBeGenerated()
    {
        if (!file_exists('public/app/etc/modules/Behat_Unique.xml')) {
            throw new \RuntimeException('Module XML file was not generated');
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

    private function checkSpecIsGenerated(SpecSpecification $specification)
    {
        $this->checkFileExists($specification);
        require($specification->getFilePath());
        $this->checkClassExists('spec\\'.$specification->getClassName());
    }

    private function checkClassIsGenerated(ClassSpecification $specification)
    {
        $this->checkFileExists($specification);
        $this->checkClassExists($specification->getClassName());
    }

    private function checkFileExists(ObjectSpecification $specification)
    {
        if (!file_exists($specification->getFilePath())) {
            throw new \RuntimeException(
                sprintf(
                    '%s class not found in %s',
                    $specification->getType(),
                    $specification->getDirectory()
                )
            );
        }
    }

    private function checkClassExists($className)
    {
        if (!class_exists($className, false)) {
            throw new \RuntimeException(sprintf("Class $className not found"));
        }
    }
}
