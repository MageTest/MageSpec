<?php

namespace MageTest\PHPSpec2\MagentoExtension\Command;

use PHPSpec2\Console\Command\DescribeCommand;

class DescribeControllerCommand extends DescribeCommand
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
            ->setName('describe:controller')
            ->setDescription('Describe a Magento Controller object')
            ->setHelp(<<<EOF
The <info>%command.name%</info> will create a skelton specification
for your Controller.
EOF
            )
        ;
    }

}
