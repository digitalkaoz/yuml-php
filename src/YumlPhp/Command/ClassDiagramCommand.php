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
use YumlPhp\Builder\ClassDiagramBuilder;
use YumlPhp\Builder\YumlClassDiagramBuilder;
use YumlPhp\Builder\ConsoleClassDiagramBuilder;

/**
 * this command generates a class diagram
 * 
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
class ClassDiagramCommand extends Command
{

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setDefinition(array(
              new InputArgument('folder', InputArgument::REQUIRED, 'the folder to scan'),
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

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $messages = $this->createBuilder($input)->build();

        if (!count($messages)) {
            $input->setOption('debug', true);
            $messages = $this->createBuilder($input)->build();
            throw new \RuntimeException('Uml Build Error ' . "\n" . join("\n", $messages ? : array()));
        }

        $output->writeln($messages);
    }

    /**
     * creates a builder (Yuml or Console)
     * 
     * @param InputInterface $input
     * @return BuilderInterface 
     */
    protected function createBuilder(InputInterface $input)
    {
        //scruffy, nofunky, plain
        //dir: LR TB RL
        //scale: 180 120 100 80 60
        $style = $input->getOption('style') ?: 'plain;dir:LR;scale:80;';
        
        $config = array(
          'withMethods' => (boolean) $input->getOption('methods'),
          'withProperties' => (boolean) $input->getOption('properties'),
          'url' => 'http://yuml.me/diagram/'.$style.'/class/',
          'debug' => $input->getOption('debug')
        );

        if ($input->getOption('console')) {
            $builder = new ConsoleClassDiagramBuilder();
        } else {
            $browser = new Browser();
            $browser->getClient()->setTimeout(10);
            $builder = new YumlClassDiagramBuilder($browser);
        }

        return $builder
                ->configure($config)
                ->setPath($input->getArgument('folder'))
                ->setFinder(new Finder())
        ;
    }

}