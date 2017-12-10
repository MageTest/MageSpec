<?php

namespace Fake;

use PhpSpec\Console\Prompter;

class YesPrompter implements Prompter
{
    /**
     * @param string $question
     * @param boolean $default
     * @return boolean
     */
    public function askConfirmation(string $question, bool $default = true) : bool
    {
        return true;
    }
}
