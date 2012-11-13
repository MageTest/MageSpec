<?php

namespace MageTest\PHPSpec2\MagentoExtension\Command;

use PHPSpec2\Console\Command\DescribeCommand;

class DescribeControllerCommand extends DescribeCommand
{
    public function __construct()
    {
        parent::__construct();

        $this->setDefinition(array(
            new InputArgument('spec', InputArgument::REQUIRED, 'Spec to describe'),
            new InputOption('src-path', null, InputOption::VALUE_REQUIRED, 'Source path', 'app/code/local'),
            new InputOption('spec-path', null, InputOption::VALUE_REQUIRED, 'Specs path', 'spec/app/code/local'),
            new InputOption('namespace', null, InputOption::VALUE_REQUIRED, 'Specs NS', 'spec\\'),
        ));
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



    protected function getSpecContentFor(array $parameters)
    {
        $template = file_get_contents(__DIR__.'/../Resources/templates/controller-spec.php');

        return strtr($template, $parameters);
    }
}
