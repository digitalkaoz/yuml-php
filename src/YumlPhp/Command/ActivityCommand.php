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

use Symfony\Component\Console\Command\Command;

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
}
