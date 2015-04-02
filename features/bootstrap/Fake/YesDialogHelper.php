<?php

namespace Fake;

use PhpSpec\Console\Prompter;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Output\OutputInterface;

class YesDialogHelper extends DialogHelper
{
    public function askConfirmation(OutputInterface $output, $question, $default = true)
    {
        return 'Y';
    }

}