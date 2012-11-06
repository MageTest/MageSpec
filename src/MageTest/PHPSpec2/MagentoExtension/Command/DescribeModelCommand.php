<?php

namespace MageTest\PHPSpec2\MagentoExtension\Command;

use PHPSpec2\Console\Command\DescribeCommand;

class DescribeModelCommand extends DescribeCommand
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('describe:model')
            ->setDescription('Describe a Magento Model object')
            ->setHelp(<<<EOF
The <info>%command.name%</info> will create a skelton specification
for your Model.
EOF
            )
        ;
    }

}