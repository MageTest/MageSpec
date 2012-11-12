<?php

namespace spec\MageTest\PHPSpec2\MagentoExtension\Loader;

use PHPSpec2\ObjectBehavior;

use PHPSpec2\Loader\Node\Specification as NodeSpecification;

use ReflectionClass;

class SpecificationsClassLoader extends ObjectBehavior
{
    function it_loads_controller_specs()
    {
        $specification = $this->loadFromfile('spec/Acme/Cms/controllers/IndexController.php');

        $specification->shouldBeLike(array(
            new NodeSpecification(
                'spec\Acme_Cms_IndexController',
                new \ReflectionClass('spec\Acme_Cms_IndexController')
            )
        ));
    }

    function it_checks_if_controller_spec_implements_magespec_controller_behavior()
    {
        $specifications = $this->loadFromfile('spec/Acme/Cms/controllers/PageController.php');

        $specifications[0]
            ->getClass()
            ->isSubclassOf('MageTest\PHPSpec2\MagentoExtension\Specification\ControllerBehavior')
            ->shouldBe(true);
    }

    function it_loads_block_specs()
    {
        $specification = $this->loadFromfile('spec/Acme/Cms/Block/Page.php');

        $specification->shouldBeLike(array(
            new NodeSpecification(
                'spec\Acme_Cms_Block_Page',
                new \ReflectionClass('spec\Acme_Cms_Block_Page')
            )
        ));
    }

    function it_loads_helper_specs()
    {
        $specification = $this->loadFromfile('spec/Acme/Cms/Helper/Data.php');

        $specification->shouldBeLike(array(
            new NodeSpecification(
                'spec\Acme_Cms_Helper_Data',
                new \ReflectionClass('spec\Acme_Cms_Helper_Data')
            )
        ));
    }

    function it_loads_setup_scripts_specs()
    {
        $specification = $this->loadFromfile('spec/Acme/Cms/sql/install-0.1.0.php');

        $specification->shouldBeLike(array(
            new NodeSpecification(
                'spec\Acme_Cms_Setup',
                new \ReflectionClass('spec\Acme_Cms_Setup')
            )
        ));
    }

    function it_checks_if_block_spec_implements_magespec_block_behavior()
    {
        $specifications = $this->loadFromfile('spec/Acme/Cms/Block/Page.php');

        $specifications[0]
            ->getClass()
            ->isSubclassOf('MageTest\PHPSpec2\MagentoExtension\Specification\BlockBehavior')
            ->shouldBe(true);
    }

    private $currentWorkingDirectory;

    function let()
    {
        $this->currentWorkingDirectory = getcwd();
        chdir($this->currentWorkingDirectory . '/fixtures');
    }

    function letgo()
    {
        chdir($this->currentWorkingDirectory);
    }
}
