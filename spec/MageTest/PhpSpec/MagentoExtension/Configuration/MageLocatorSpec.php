<?php

namespace spec\MageTest\PhpSpec\MagentoExtension\Configuration;

use PhpSpec\ObjectBehavior;

class MageLocatorSpec extends ObjectBehavior
{
    function it_sets_default_configuration()
    {
        $configuration = [];

        $params = ['mage_locator' => $configuration];

        $this->beConstructedThrough('fromParams', [$params]);
        $this->getNamespace()->shouldBe('');
        $this->getSpecPrefix()->shouldBe('spec');
        $this->getSrcPath()->shouldBe('src');
        $this->getSpecPath()->shouldBe('.');
        $this->getCodePool()->shouldBe('local');
    }

    function it_sets_the_correct_configuration()
    {
        $namesapce = 'Magento';
        $specPrefix = 'spec';
        $srcPath = 'public/app/code';
        $specPath = 'spec/public/app/code';
        $codePool = 'community';
        $configuration = [
            'namespace' => $namesapce,
            'spec_prefix' => $specPrefix,
            'src_path' => $srcPath,
            'spec_path' => $specPath,
            'code_pool' => $codePool
        ];

        $params = ['mage_locator' => $configuration];

        $this->beConstructedThrough('fromParams', [$params]);
        $this->getNamespace()->shouldBe($namesapce);
        $this->getSpecPrefix()->shouldBe($specPrefix);
        $this->getSrcPath()->shouldBe($srcPath . '/');
        $this->getSpecPath()->shouldBe($specPath . '/');
        $this->getCodePool()->shouldBe($codePool);
    }
}
