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
use YumlPhp\Builder;

/**
 * this common command
 * 
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
abstract class BaseCommand extends Command
{
    private $builder;
    
    /**
     * @inheritDoc
     */
    protected function createBuilder(InputInterface $input)
    {
        if($input->getOption('console')) {
            $class = static::$consoleBuilder;
            $this->builder = new $class();
        } else {
            $browser = new Browser();
            $browser->getClient()->setTimeout(10);
            $class = static::$httpBuilder;
            $this->builder = new $class($browser);
        }
        
        //scruffy, nofunky, plain
        //dir: LR TB RL
        //scale: 180 120 100 80 60
        $style = $input->getOption('style') ?: 'plain;dir:LR;scale:80;';

        if($this->builder instanceof Builder\Http\ClassesBuilder || $this->builder instanceof Builder\Console\ClassesBuilder) {
            $type = 'class';
        } elseif($this->builder instanceof Builder\Http\ActivityBuilder || $this->builder instanceof Builder\Console\ActivityBuilder) {
            $type = 'activity';
        } elseif($this->builder instanceof Builder\Http\UseCaseBuilder || $this->builder instanceof Builder\Console\UseCaseBuilder) {
            $type = 'usecase';
        }
        
        if (!isset($type)) {
            throw new \RuntimeException('no valid builder passed');
        }
        
        $config = array(
          'url' => 'http://yuml.me/diagram/'.$style.'/'.$type.'/',
          'debug' => $input->getOption('debug')
        );

        return $this->builder
            ->configure($config)
            ->setPath($input->getArgument('source'))
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
}