<?php

/*
 * This file is part of yuml-php
 *
 * (c) Robert Schönthal <seroscho@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace YumlPhp\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use YumlPhp\Command;

/**
 * The console application that handles the commands
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
class Application extends BaseApplication
{

    public function __construct()
    {
        parent::__construct('yuml-php', '@package_version@');
    }

    /**
     * {@inheritDoc}
     */
    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        if (null === $output) {
            $styles['highlight'] = new OutputFormatterStyle('red');
            $styles['warning'] = new OutputFormatterStyle('black', 'yellow');
            $styles['note'] = new OutputFormatterStyle('yellow');
            $styles['hint'] = new OutputFormatterStyle('cyan');
            $formatter = new OutputFormatter(null, $styles);
            $output = new ConsoleOutput(ConsoleOutput::VERBOSITY_NORMAL, null, $formatter);
        }

        return parent::run($input, $output);
    }

    /**
     * {@inheritDoc}
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->registerCommands();

        return parent::doRun($input, $output);
    }

    /**
     * Initializes all the yuml-php commands
     */
    protected function registerCommands()
    {
        //class diagram generator
        $this->add(new Command\ClassesCommand());

        //activity diagram generator
        $this->add(new Command\ActivityCommand());

        //use-case diagram generator
        $this->add(new Command\UseCaseCommand());
    }

}
