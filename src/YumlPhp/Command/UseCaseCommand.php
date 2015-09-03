<?php

namespace YumlPhp\Command;

/**
 * this command generates an use-case diagram.
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
class UseCaseCommand extends BaseCommand
{
    /**
     * {@inheritdoc}
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
}
