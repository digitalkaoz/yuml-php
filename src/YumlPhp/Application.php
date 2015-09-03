<?php

namespace YumlPhp;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * The console application that handles the commands.
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
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        parent::__construct('yuml-php', '@package_version@');

        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->registerCommands();

        return parent::doRun($input, $output);
    }

    /**
     * Initializes the commands.
     */
    private function registerCommands()
    {
        $this->add($this->container['command.classes']);
        $this->add($this->container['command.activity']);
        $this->add($this->container['command.use_case']);
    }
}
