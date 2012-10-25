<?php

namespace spec\MageTest\PHPSpec2\MagentoExtension;

use PHPSpec2\Specification;

class Bootstrap implements Specification
{
    function described_with($app)
    {
        $app->isAmockOf('PHPSpec2\Magento\Bootstrap\App');
        $this->bootstrap->isAnInstanceOf(
            'PHPSpec2\Magento\Bootstrap',
            array($app)
        );
    }
    
    function it_should_not_throw_exception_on_init($app)
    {
        $this->bootstrap->init();
    }
    
    function it_should_not_throw_excpetion_on_app($app)
    {
        $this->bootstrap->app();
    }
    
    function it_should_not_throw_exception_on_run($app)
    {
        $this->bootstrap->run();
    }
    
    function it_should_not_die_on_dispatch($app)
    {
        $this->bootstrap->app()->dispatch('/');
    }
}