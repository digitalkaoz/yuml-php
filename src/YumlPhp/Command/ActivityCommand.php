<?php

namespace YumlPhp\Command;

/**
 * this command generates an activity diagram.
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
class ActivityCommand extends BaseCommand
{
    /**
     * {@inheritdoc}
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
