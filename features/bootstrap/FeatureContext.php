<?php

use Behat\Behat\Context\SnippetAcceptingContext;
use Fake\YesPrompter;
use OutputSpecification\ClassSpecification;
use OutputSpecification\ObjectSpecification;
use OutputSpecification\SpecSpecification;
use PhpSpec\Console\Application;
use spec\MageTest\PhpSpec\DirectorySeparator;
use Symfony\Component\Console\Tester\ApplicationTester;
use Symfony\Component\Filesystem\Exception\IOException;
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
        try {
            $this->filesystem->remove(
                [
                    DirectorySeparator::replacePathWithDirectorySeperator('spec/public'),
                    DirectorySeparator::replacePathWithDirectorySeperator('spec/Behat'),
                    DirectorySeparator::replacePathWithDirectorySeperator('public'),
                    DirectorySeparator::replacePathWithDirectorySeperator('src/Behat'),
                ]
            );
        } catch (IOException $e) {
            //ignoring exception
        }
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
        $this->configFile = DirectorySeparator::replacePathWithDirectorySeperator(
            __DIR__ . '/config_files/standard.yml'
        );
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
            [
                'command' => sprintf('describe:%s', $objectType),
                '--no-interaction' => true,
                '--config' => $this->configFile,
                'alias' => strtolower($this->namespace) . DirectorySeparator::replacePathWithDirectorySeperator('/test')
            ],
            ['decorated' => false]
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
     * @Given there is a :objectType spec
     */
    public function thatThereIsASpec($objectType)
    {
        $moduleName = $this->moduleName ?: 'Spec';

        switch ($objectType) {
            case 'controller':
                $dir = 'controllers';
                $filename = 'TestControllerSpec';
                $className = "Behat_${moduleName}_TestController";
                break;
            default:
                $dir = ucfirst($objectType);
                $filename = 'TestSpec';
                $className = "Behat_${moduleName}_${dir}_Test";
        }

        $this->currentSpec = DirectorySeparator::replacePathWithDirectorySeperator(
            "spec/public/app/code/local/Behat/$moduleName/$dir/$filename.php"
        );

        $this->filesystem->dumpFile(
            $this->currentSpec,
            $this->updateClassNameInTemplate($className, $this->getTemplate($objectType))
        );
    }

    /**
     * @When Magespec runs the spec
     */
    public function magespecRunsTheSpec()
    {
        $this->applicationTester->run(
            [
                'command' =>'run',
                '--config' =>  $this->configFile,
                '--no-rerun' => true,
                $this->currentSpec
            ],
            [
                'interactive' => true,
                'decorated' => false
            ]
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
     * @Given there is a new module
     */
    public function thereIsANewModule()
    {
        $this->moduleName = $moduleName = 'Unique' . self::$uniqueCount;
        PHPUnit_Framework_Assert::assertFileNotExists(
            DirectorySeparator::replacePathWithDirectorySeperator("public/app/code/local/Behat/$moduleName")
        );
    }

    /**
     * @Then the module XML file should be generated
     */
    public function theModuleXmlFileShouldBeGenerated()
    {
        $unique = self::$uniqueCount;
        if (!file_exists(DirectorySeparator::replacePathWithDirectorySeperator("public/app/etc/modules/Behat_Unique$unique.xml"))) {
            throw new \RuntimeException('Module XML file was not generated');
        }
    }

    /**
     * @Then the config XML file should be generated
     */
    public function theConfigXmlFileShouldBeGenerated()
    {
        $moduleName = $this->moduleName;
        if (!file_exists(DirectorySeparator::replacePathWithDirectorySeperator("public/app/code/local/Behat/$moduleName/etc/config.xml"))) {
            throw new \RuntimeException('Config XML file was not generated');
        }
    }

    /**
     * @Then the config XML file should contain a :objectType element
     */
    public function theConfigXmlFileShouldContainAnElement($objectType)
    {
        $moduleName = $this->moduleName;
        if (!file_exists(DirectorySeparator::replacePathWithDirectorySeperator("public/app/code/local/Behat/$moduleName/etc/config.xml"))) {
            throw new \RuntimeException('Config XML file was not generated');
        }

        switch ($objectType) {
            case 'controller':
                $path = sprintf('frontend/routers/%s/args/module', strtolower($moduleName));
                $expectedClass = 'Behat_' . $moduleName;
                break;
            default:
                $path = sprintf('global/%ss/behat_%s/class', $objectType, strtolower($moduleName));
                $expectedClass = sprintf('Behat_%s_%s', $moduleName, ucfirst($objectType));
        }

        $xml = new \SimpleXMLElement(
            DirectorySeparator::replacePathWithDirectorySeperator("public/app/code/local/Behat/$moduleName/etc/config.xml"),
            0,
            true
        );

        $result = $xml->xpath(DirectorySeparator::replacePathWithDirectorySeperator($path));

        if (!$result || count($result) === 0) {
            throw new \RuntimeException('Element not found in config XML');
        }

        PHPUnit_Framework_Assert::assertEquals($expectedClass, (string) $result[0]);
    }

    /**
     * @When I describe a non Magento object
     */
    public function iDescribeANonMagentoObject()
    {
        $this->applicationTester->run(
            [
                'command' => 'describe',
                '--no-interaction' => true,
                '--config' =>  $this->configFile,
                'class' => DirectorySeparator::replacePathWithDirectorySeperator('Behat/Test')
            ],
            ['decorated' => false]
        );
    }

    /**
     * @Then the non Magento spec should be generated
     */
    public function theNonMagentoSpecShouldBeGenerated()
    {
        $this->checkSpecIsGenerated(new SpecSpecification(
            'non_magento',
            'spec/Behat/TestSpec.php',
            'Behat\TestSpec'
        ));
    }

    /**
     * @Given there is a spec for a new non Magento object
     */
    public function thereIsASpecForANewNonMagentoObject()
    {
        $template = $this->getTemplate('non_magento');
        $this->currentSpec = DirectorySeparator::replacePathWithDirectorySeperator("spec/Behat/TestSpec.php");
        $this->filesystem->copy($template, $this->currentSpec);
    }

    /**
     * @Then the non Magento object should be generated
     */
    public function theNonMagentoObjectShouldBeGenerated()
    {
        require DirectorySeparator::replacePathWithDirectorySeperator('src/Behat/Test.php');
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
        $application->getContainer()->set('console.prompter', new YesPrompter());

        return new ApplicationTester($application);
    }

    private function checkSpecIsGenerated(SpecSpecification $specification)
    {
        $this->checkFileExists($specification);
        require($specification->getFilePath());
        $this->checkClassExists('spec\\'.$specification->getClassName());

        $this->checkSpecIsCorrect($specification);
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

    private function checkSpecIsCorrect(SpecSpecification $specSpecification)
    {
        $expectedSpec = $this->updateClassNameInTemplate(
            $specSpecification->getClassName(),
            $this->getTemplate($specSpecification->getType())
        );

        $generatedSpec = file_get_contents($specSpecification->getFilePath());

        PHPUnit_Framework_Assert::assertEquals($expectedSpec, $generatedSpec);
    }

    private function getTemplate($objectType)
    {
        switch (strtolower($objectType)) {
            case 'controller':
                $templateType = 'controller';
                break;
            case 'non_magento':
                $templateType = 'non_magento';
                break;
            default:
                $templateType = 'default';
        }

        return DirectorySeparator::replacePathWithDirectorySeperator(__DIR__ . "/templates/specs/$templateType.template");
    }

    private function updateClassNameInTemplate($className, $template)
    {
        return str_replace(
            '%class_name%',
            preg_replace('/Spec$/', '', $className),
            file_get_contents($template)
        );
    }
}
