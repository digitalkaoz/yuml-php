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
use Symfony\Component\Finder\Finder;
use Buzz\Browser;

use YumlPhp\Builder\BuilderInterface;
use YumlPhp\Builder\Http\ClassesBuilder as HttpBuilder;
use YumlPhp\Builder\Console\ClassesBuilder as ConsoleBuilder;

/**
 * this command generates a class diagram
 * 
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
class ClassesCommand extends BaseCommand
{
    static $httpBuilder = 'YumlPhp\Builder\Http\ClassesBuilder';
    static $consoleBuilder = 'YumlPhp\Builder\Console\ClassesBuilder';

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setDefinition(array(
              new InputArgument('source', InputArgument::REQUIRED, 'the folder to scan'),
              new InputOption('console', null, InputOption::VALUE_NONE, 'log to console'),
              new InputOption('debug', null, InputOption::VALUE_NONE, 'debug'),
              new InputOption('properties', null, InputOption::VALUE_NONE, 'build with properties'),
              new InputOption('methods', null, InputOption::VALUE_NONE, 'build with methods'),
              new InputOption('style', null, InputOption::VALUE_NONE, 'yuml style options')
            ))
            ->setDescription('creates a class diagram of a given folder')
            ->setHelp(<<<EOT
The <info>class-diagram</info> command generates a class diagram from all classes in the given folder

<info>yuml-php classes src/</info> builds class diagram for folder src/
EOT
            )
            ->setName('classes')
        ;
    }
}