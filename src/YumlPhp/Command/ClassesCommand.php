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
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        parent::configure();

        $this->getDefinition()->addOption(new InputOption('properties', null, InputOption::VALUE_NONE, 'build with properties'));
        $this->getDefinition()->addOption(new InputOption('methods', null, InputOption::VALUE_NONE, 'build with methods'));

        $this
            ->setName('classes')
            ->setDescription('creates a class diagram of a given folder')
            ->setHelp(<<<EOT
The <info>class-diagram</info> command generates a class diagram from all classes in the given folder

<info>yuml-php classes src/</info> builds class diagram for folder src/
EOT
        );
    }

    /**
     * @inheritDoc
     */
    protected function getBuilderConfig(BuilderInterface $builder, InputInterface $input)
    {
        //scruffy, nofunky, plain
        //dir: LR TB RL
        //scale: 180 120 100 80 60
        $style = $input->getOption('style') ? : 'plain;dir:LR;scale:80;';
        $type = $builder->getType();

        return array(
            'url'            => 'http://yuml.me/diagram/' . $style . '/' . $type . '/',
            'debug'          => $input->getOption('debug'),
            'withMethods'    => $input->getOption('methods'),
            'withProperties' => $input->getOption('properties'),
            'style'          => $style,
            'autoload_path'  => $input->getArgument('source')
        );
    }
}