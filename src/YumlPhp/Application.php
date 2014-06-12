<?php

/*
 * This file is part of yuml-php
 *
 * (c) Robert Schönthal <seroscho@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace YumlPhp;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * The console application that handles the commands
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
class Application extends BaseApplication
{
    /**
     * @var \Pimple
     */
    private $container;

    /**
     * @param \Pimple $container
     */
    public function __construct(\Pimple $container)
    {
        parent::__construct('yuml-php', '@package_version@');

        $this->container = $container;
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
     * Initializes the commands
     */
    private function registerCommands()
    {
        $this->add($this->container['command.classes']);
        $this->add($this->container['command.activity']);
        $this->add($this->container['command.use_case']);
    }
}
