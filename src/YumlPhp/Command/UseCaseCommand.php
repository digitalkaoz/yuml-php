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
 * this command generates an use-case diagram
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
class UseCaseCommand extends BaseCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('use-case')
            ->setDescription('creates an use-case diagram from a file')
            ->setHelp(<<<EOT
The <info>use-case</info> command generates an use-case diagram from a file

<info>yuml-php use-case src/use-cases.txt</info> builds an use-case diagram for the file
EOT
        );
    }

    /**
     * @inheritDoc
     */
    protected function getBuilderConfig(BuilderInterface $builder, InputInterface $input)
    {
        $style = $input->getOption('style') ? : 'plain;dir:LR;scale:80;';
        $type = $builder->getType();

        return array(
            'url'   => 'http://yuml.me/diagram/' . $style . '/' . $type . '/',
            'debug' => $input->getOption('debug'),
            'style' => $input->getOption('style') ? : 'plain;dir:LR;scale:80;'
        );
    }
}
