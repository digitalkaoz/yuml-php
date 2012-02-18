<?php

/*
 * This file is part of yuml-php
 *
 * (c) Robert Schönthal <seroscho@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace YumlPhp\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Finder\Finder;
use Buzz\Browser;

use YumlPhp\Builder\BuilderInterface;
use YumlPhp\Builder\Console\ActivityBuilder as ConsoleBuilder;

/**
 * this command generates an activity diagram
 * 
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
class ActivityCommand extends BaseCommand
{
    static protected $httpBuilder = 'YumlPhp\Builder\Http\ActivityBuilder';
    static protected $consoleBuilder = 'YumlPhp\Builder\Console\ActivityBuilder';

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setDefinition(array(
              new InputArgument('source', InputArgument::REQUIRED, 'the file to read'),
              new InputOption('console', null, InputOption::VALUE_NONE, 'log to console'),
              new InputOption('debug', null, InputOption::VALUE_NONE, 'debug'),
              new InputOption('style', null, InputOption::VALUE_NONE, 'yuml style options')
            ))
            ->setDescription('creates an activity diagram from a file')
            ->setHelp(<<<EOT
The <info>activity</info> command generates an activity diagram from a file

<info>yuml-php activity src/activities.txt</info> builds an activity diagram for the file
EOT
            )
            ->setName('activity')
        ;
    }    
}