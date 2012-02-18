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
use YumlPhp\Builder\Http\UseCaseBuilder as HttpBuilder;
use YumlPhp\Builder\Console\UseCaseBuilder as ConsoleBuilder;

/**
 * this command generates an use-case diagram
 * 
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
class UseCaseCommand extends BaseCommand
{
    static $httpBuilder = 'YumlPhp\Builder\Http\UseCaseBuilder';
    static $consoleBuilder = 'YumlPhp\Builder\Console\UseCaseBuilder';

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
            ->setDescription('creates an use-case diagram from a file')
            ->setHelp(<<<EOT
The <info>use-case</info> command generates an use-case diagram from a file

<info>yuml-php use-case src/use-cases.txt</info> builds an use-case diagram for the file
EOT
            )
            ->setName('use-case')
        ;
    }
}