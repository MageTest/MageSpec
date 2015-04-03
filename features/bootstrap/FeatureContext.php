<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\SnippetAcceptingContext;
use Fake\YesDialogHelper;
use OutputSpecification\ClassSpecification;
use OutputSpecification\ObjectSpecification;
use OutputSpecification\SpecSpecification;
use PhpSpec\Console\Application;
use Symfony\Component\Console\Tester\ApplicationTester;
use Symfony\Component\Filesystem\Filesystem;


/**
 * Behat context class.
 */
class FeatureContext implements SnippetAcceptingContext
{
    /**
     * @var int
     */
    private static $uniqueCount = 1;

    /**
     * @var string
     */
    private $configFile;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var string
     */
    private $moduleName;

    /**
     * @var ApplicationTester
     */
    private $applicationTester;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var string
     */
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
     * @BeforeScenario
     */
    public function resetModuleData()
    {
        $this->moduleName = null;
    }

    /**
     * @AfterScenario
     */
    public function removeTemporaryDirectories()
    {
        $this->filesystem->remove(
            array(
                'spec/public',
                'spec/Behat',
                'public',
                'src/Behat',
            )
        );
    }

    /**
     * @AfterScenario
     */
    public function incrementUniqueCounter()
    {
        self::$uniqueCount++;
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
            array(
                'command' => sprintf('describe:%s', $objectType),
                '--no-interaction' => true,
                '--config' => $this->configFile,
                'alias' => strtolower($this->namespace) . '/test'
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
     * @Given there is a :objectType spec
     */
    public function thatThereIsASpec($objectType)
    {
        $moduleName = $this->moduleName ?: 'Spec';

        switch ($objectType) {
            case 'controller':
                $dir = 'controllers';
                $filename = 'TestControllerSpec';
                $templateType = 'controller';
                $className = "Behat_${moduleName}_TestController";
                break;
            case 'resource model':
                $dir = 'Model/Resource';
                $filename = 'TestSpec';
                $templateType = 'default';
                $className = "Behat_${moduleName}_Model_Resource_Test";
                break;
            default:
                $dir = ucfirst($objectType);
                $filename = 'TestSpec';
                $templateType = 'default';
                $className = "Behat_${moduleName}_${dir}_Test";
        }

        $template = __DIR__ . "/templates/specs/$templateType.template";
        $this->currentSpec = "spec/public/app/code/local/Behat/$moduleName/$dir/$filename.php";

        $this->filesystem->dumpFile(
            $this->currentSpec,
            str_replace(
                '%class_name%',
                $className,
                file_get_contents($template)
            )
        );
    }

    /**
     * @When Magespec runs the spec
     */
    public function magespecRunsTheSpec()
    {
        $this->applicationTester->run(
            array(
                'command' =>'run',
                '--config' =>  $this->configFile,
                '--no-rerun' => true,
                $this->currentSpec
            ),
            array(
                'interactive' => true,
                'decorated' => false
            )
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
     * @Given there is a new module
     */
    public function thereIsANewModule()
    {
        $this->moduleName = $moduleName = 'Unique' . self::$uniqueCount;
        expect($this->filesystem->exists("public/app/code/local/Behat/$moduleName"))->toBe(false);
    }

    /**
     * @Then the module XML file should be generated
     */
    public function theModuleXmlFileShouldBeGenerated()
    {
        $unique = self::$uniqueCount;
        if (!file_exists("public/app/etc/modules/Behat_Unique$unique.xml")) {
            throw new \RuntimeException('Module XML file was not generated');
        }
    }

    /**
     * @Then the config XML file should be generated
     */
    public function theConfigXmlFileShouldBeGenerated()
    {
        $moduleName = $this->moduleName;
        if (!file_exists("public/app/code/local/Behat/$moduleName/etc/config.xml")) {
            throw new \RuntimeException('Config XML file was not generated');
        }
    }

    /**
     * @Then the config XML file should contain a :objectType element
     */
    public function theConfigXmlFileShouldContainAnElement($objectType)
    {
        $moduleName = $this->moduleName;
        if (!file_exists("public/app/code/local/Behat/$moduleName/etc/config.xml")) {
            throw new \RuntimeException('Config XML file was not generated');
        }

        switch ($objectType) {
            case 'controller':
                $path = sprintf('frontend/routers/%s/args/module', strtolower($moduleName));
                $expectedClass = 'Behat_' . $moduleName;
                break;
            case 'resource model':
                $path = sprintf('global/models/behat_%s_resource/class', strtolower($moduleName));
                $expectedClass = "Behat_${moduleName}_Model_Resource";
                break;
            default:
                $path = sprintf('global/%ss/behat_%s/class', $objectType, strtolower($moduleName));
                $expectedClass = sprintf('Behat_%s_%s', $moduleName, ucfirst($objectType));
        }

        $xml = new \SimpleXMLElement("public/app/code/local/Behat/$moduleName/etc/config.xml", 0, true);
        $result = $xml->xpath($path);

        if (!$result || count($result) === 0) {
            throw new \RuntimeException('Element not found in config XML');
        }

        expect((string) $result[0])->toBe($expectedClass);
    }

    /**
     * @When I describe a non Magento object
     */
    public function iDescribeANonMagentoObject()
    {
        $this->applicationTester->run(
            array(
                'command' => 'describe',
                '--no-interaction' => true,
                '--config' =>  $this->configFile,
                'class' => 'Behat/Test'
            ),
            array('decorated' => false)
        );
    }

    /**
     * @Then the non Magento spec should be generated
     */
    public function theNonMagentoSpecShouldBeGenerated()
    {
        $this->checkSpecIsGenerated(new SpecSpecification(
            'spec',
            'spec/Behat/TestSpec.php',
            'Behat\TestSpec'
        ));
    }

    /**
     * @Given there is a spec for a new non Magento object
     */
    public function thereIsASpecForANewNonMagentoObject()
    {
        $template = __DIR__ . "/templates/specs/non_magento.template";
        $this->currentSpec = "spec/Behat/TestSpec.php";
        $this->filesystem->copy($template, $this->currentSpec);
    }

    /**
     * @Then the non Magento object should be generated
     */
    public function theNonMagentoObjectShouldBeGenerated()
    {
        require 'src/Behat/Test.php';
        $this->checkClassIsGenerated(new ClassSpecification(
            'Test',
            'src/Behat/Test.php',
            'Behat\Test'
        ));
    }

    /**
     * @return ApplicationTester
     */
    private function createApplicationTester()
    {
        $application = new Application('version');
        $application->setAutoExit(false);
        $application->getHelperSet()->set(new YesDialogHelper());

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
