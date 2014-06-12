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

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Command\Command;

use YumlPhp\Builder\BuilderInterface;

/**
 * this command generates an activity diagram
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
class ActivityCommand extends BaseCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('activity')
            ->setDescription('creates an activity diagram from a file')
            ->setHelp(<<<EOT
The <info>activity</info> command generates an activity diagram from a file

<info>yuml-php activity src/activities.txt</info> builds an activity diagram for the file
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
            'url'   => 'http://yuml.me/diagram/' . $style . '/' . $type . '/',
            'debug' => $input->getOption('debug'),
            'style' => $input->getOption('style') ? : 'plain;dir:LR;scale:80;'
        );
    }

}
