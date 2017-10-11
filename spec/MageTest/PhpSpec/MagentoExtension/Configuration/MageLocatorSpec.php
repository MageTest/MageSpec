<?php

namespace spec\MageTest\PhpSpec\MagentoExtension\Configuration;

use PhpSpec\ObjectBehavior;
use spec\MageTest\PhpSpec\DirectorySeparator;

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
        $srcPath = DirectorySeparator::replacePathWithDirectorySeperator('public/app/code');
        $specPath = DirectorySeparator::replacePathWithDirectorySeperator('spec/public/app/code');
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
        $this->getSrcPath()->shouldBe(DirectorySeparator::replacePathWithDirectorySeperator($srcPath . '/'));
        $this->getSpecPath()->shouldBe(DirectorySeparator::replacePathWithDirectorySeperator($specPath . '/'));
        $this->getCodePool()->shouldBe($codePool);
    }
}
