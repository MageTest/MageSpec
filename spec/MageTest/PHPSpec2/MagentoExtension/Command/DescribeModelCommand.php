<?php

namespace spec\MageTest\PHPSpec2\MagentoExtension\Command;

use PHPSpec2\ObjectBehavior;

class DescribeModelCommand extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('MageTest\PHPSpec2\MagentoExtension\Command\DescribeModelCommand');
    }

    function it_should_extend_phpspec2_describe_command()
    {
        $this->shouldBeAnInstanceOf("PHPSpec2\Console\Command\DescribeCommand");
    }

    function it_should_be_called_describe_model()
    {
        $this->getName()->shouldReturn('describe:model');
    }
}
